<?php

class TEC_LuminAI_Agent
{
    // Allow public by default; set TEC_LUMINAI_AGENT_PUBLIC to false in wp-config.php to require logged-in users
    public static function permission_check() {
        $isPublic = defined('TEC_LUMINAI_AGENT_PUBLIC') ? (bool) constant('TEC_LUMINAI_AGENT_PUBLIC') : true;
        if ($isPublic) { return true; }
        return function_exists('is_user_logged_in') ? (bool) call_user_func('is_user_logged_in') : false;
    }

    public static function handle_request($request)
    {
        $payload = is_object($request) && method_exists($request, 'get_json_params') ? $request->get_json_params() : [];
        $messages = isset($payload['messages']) && is_array($payload['messages']) ? $payload['messages'] : [];
        if (empty($messages)) {
            if (function_exists('rest_ensure_response')) { return call_user_func('rest_ensure_response', ['error' => 'messages array required']); }
            return ['error' => 'messages array required'];
        }

        // Basic rate limiting per IP (optional; can be tuned or disabled)
        if (function_exists('get_transient') && function_exists('set_transient')) {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
            $key = 'tec_luminai_rl_' . md5($ip);
            $count = (int) call_user_func('get_transient', $key);
            $limit = defined('TEC_LUMINAI_RATE_LIMIT') ? (int) constant('TEC_LUMINAI_RATE_LIMIT') : 30; // per 10 minutes
            if ($count >= $limit) {
                if (function_exists('rest_ensure_response')) { return call_user_func('rest_ensure_response', ['error' => 'rate_limited']); }
                return ['error' => 'rate_limited'];
            }
            $minute = defined('MINUTE_IN_SECONDS') ? (int) constant('MINUTE_IN_SECONDS') : 60;
            call_user_func('set_transient', $key, $count + 1, 10 * $minute);
        }

        try {
            // Prefer Azure OpenAI if configured
            $useAzure = defined('AZURE_OPENAI_ENDPOINT') && constant('AZURE_OPENAI_ENDPOINT')
                && defined('AZURE_OPENAI_KEY') && constant('AZURE_OPENAI_KEY')
                && defined('AZURE_OPENAI_DEPLOYMENT_NAME') && constant('AZURE_OPENAI_DEPLOYMENT_NAME');
            if ($useAzure) {
                $reply = self::call_azure_openai($messages);
            } else {
                $reply = self::call_openai($messages);
            }
            $resp = ['reply' => $reply];
            if (function_exists('rest_ensure_response')) { return call_user_func('rest_ensure_response', $resp); }
            return $resp;
        } catch (\Exception $e) {
            $err = ['error' => $e->getMessage()];
            if (function_exists('rest_ensure_response')) { return call_user_func('rest_ensure_response', $err); }
            return $err;
        }
    }

    private static function call_openai(array $messages): string
    {
        $apiKey = defined('TEC_OPENAI_API_KEY') ? constant('TEC_OPENAI_API_KEY') : (defined('OPENAI_API_KEY') ? constant('OPENAI_API_KEY') : '');
        if (!$apiKey) { throw new \Exception('OpenAI API key missing. Define TEC_OPENAI_API_KEY (or OPENAI_API_KEY) in wp-config.php'); }
        $base = (defined('TEC_OPENAI_API_BASE') && constant('TEC_OPENAI_API_BASE')) ? constant('TEC_OPENAI_API_BASE') : 'https://api.openai.com/v1';
        $model = (defined('TEC_OPENAI_MODEL') && constant('TEC_OPENAI_MODEL')) ? constant('TEC_OPENAI_MODEL') : 'gpt-4o-mini';

        $body = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.7,
        ];
        $url = (function_exists('trailingslashit') ? call_user_func('trailingslashit', $base) : rtrim($base, '/') . '/') . 'chat/completions';
        $resp = function_exists('wp_remote_post') ? call_user_func('wp_remote_post', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'body' => (function_exists('wp_json_encode') ? call_user_func('wp_json_encode', $body) : json_encode($body)),
            'timeout' => 60,
        ]) : null;
        if (!$resp) { throw new \Exception('WordPress HTTP functions unavailable'); }
        if (function_exists('is_wp_error') && call_user_func('is_wp_error', $resp)) { throw new \Exception($resp->get_error_message()); }
        $code = function_exists('wp_remote_retrieve_response_code') ? call_user_func('wp_remote_retrieve_response_code', $resp) : 200;
        $bodyStr = function_exists('wp_remote_retrieve_body') ? call_user_func('wp_remote_retrieve_body', $resp) : '';
        $data = json_decode($bodyStr, true);
        if ($code !== 200) { throw new \Exception('OpenAI HTTP ' . $code . ': ' . $bodyStr); }
        $txt = $data['choices'][0]['message']['content'] ?? '';
        return trim((string)$txt);
    }

    private static function call_azure_openai(array $messages): string
    {
        $endpoint = constant('AZURE_OPENAI_ENDPOINT');
        $apiKey   = constant('AZURE_OPENAI_KEY');
        $version  = (defined('AZURE_OPENAI_VERSION') && constant('AZURE_OPENAI_VERSION')) ? constant('AZURE_OPENAI_VERSION') : '2024-05-01-preview';
        $deploy   = constant('AZURE_OPENAI_DEPLOYMENT_NAME');
        if (!$endpoint || !$apiKey || !$deploy) { throw new \Exception('Azure OpenAI missing endpoint/key/deployment'); }

        $url = rtrim($endpoint, '/') . '/openai/deployments/' . rawurlencode($deploy) . '/chat/completions?api-version=' . rawurlencode($version);
        $body = [
            'messages' => $messages,
            'temperature' => 0.7,
        ];
        $resp = function_exists('wp_remote_post') ? call_user_func('wp_remote_post', $url, [
            'headers' => [
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ],
            'body' => (function_exists('wp_json_encode') ? call_user_func('wp_json_encode', $body) : json_encode($body)),
            'timeout' => 60,
        ]) : null;
        if (!$resp) { throw new \Exception('WordPress HTTP functions unavailable'); }
        if (function_exists('is_wp_error') && call_user_func('is_wp_error', $resp)) { throw new \Exception($resp->get_error_message()); }
        $code = function_exists('wp_remote_retrieve_response_code') ? call_user_func('wp_remote_retrieve_response_code', $resp) : 200;
        $bodyStr = function_exists('wp_remote_retrieve_body') ? call_user_func('wp_remote_retrieve_body', $resp) : '';
        $data = json_decode($bodyStr, true);
        if ($code !== 200) { throw new \Exception('Azure OpenAI HTTP ' . $code . ': ' . $bodyStr); }
        $txt = $data['choices'][0]['message']['content'] ?? '';
        return trim((string)$txt);
    }
}
