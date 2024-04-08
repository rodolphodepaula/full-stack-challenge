<?php

namespace Tests;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $displayApiAppRequestResponse = false;
    private $response;

    public function api($method, $url, $params = [], $headers = [])
    {
        if (! empty($headers)) {
            $this->withHeaders($headers);
        }

        $this->response = $this->json($method, '/api/'.$url, $params);
        $this->displayApiAppRequestResponse($method, $url, $params);

        return $this->response;
    }

    public function setAuthUser($user)
    {
        $this->actingAs($user);
    }

    public function authUser($user): User
    {
        $this->actingAs($user, 'api');

        return $user;
    }

    public function responseStructDoc(string $docName)
    {
        $json = file_get_contents(storage_path().'/responses/'.$docName.'.json');
        if (empty($json)) {
            return false;
        }

        $data = json_decode($json, true);

        if (empty($data)) {
            dd(json_last_error_msg());

            return false;
        }

        $content = $data['data'];

        if (ctype_digit(implode('', array_keys($content)))) {
            return [
                'data' => [
                    '*' => $this->convertArrayInKey($content[0]),
                ],
            ];
        }

        return [
            'data' => $this->convertArrayInKey($content),
        ];
    }

    private function convertArrayInKey($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                if (ctype_digit(implode('', array_keys($value)))) {
                    $result[$key]['*'] = $this->convertArrayInKey($value[0]);
                    continue;
                }

                $result[$key] = $this->convertArrayInKey($value);
            }

            $result[] = $key;
        }

        return $result;
    }

    public function displayApiAppRequestResponse($method, $url, $params = [])
    {
        if (! $this->displayApiAppRequestResponse) {
            return;
        }

        $dataRaw = '';

        if (! empty($params)) {
            if ($method == 'GET') {
                $url .= '?'.urldecode(http_build_query($params));
            } else {
                $dataRaw = "--data-raw '".json_encode($params, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."'";
            }
        }

        $payload = json_encode($params, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo "\n..... Request ....\n\n";
        echo <<<CURL
curl --location --request $method 'https://<url api deskbee>/$url' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer <token>' \
$dataRaw
CURL;

        echo "\n..... Response ....\n\n";
        echo $this->response->getStatusCode().' '.$this->response->statusText()."\n";
        echo json_encode($this->response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
