<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiDocumentationController extends Controller
{
    public function show(Request $request)
    {
        return view('api-docs');
    }

    public function downloadPostmanCollection(Request $request)
    {
        $baseUrl = url('/api');

        $collection = [
            'info' => [
                'name' => 'CRM API',
                '_postman_id' => (string) \Illuminate\Support\Str::uuid(),
                'description' => 'Coleccion Postman para la API del CRM',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'auth' => [
                'type' => 'bearer',
                'bearer' => [
                    [
                        'key' => 'token',
                        'value' => '{{token}}',
                        'type' => 'string',
                    ],
                ],
            ],
            'variable' => [
                [
                    'key' => 'base_url',
                    'value' => $baseUrl,
                    'type' => 'string',
                ],
                [
                    'key' => 'token',
                    'value' => '',
                    'type' => 'string',
                ],
                [
                    'key' => 'company_id',
                    'value' => '1',
                    'type' => 'string',
                ],
                [
                    'key' => 'lead_id',
                    'value' => '1',
                    'type' => 'string',
                ],
            ],
            'item' => [
                [
                    'name' => 'Companies',
                    'item' => [
                        $this->requestItem('List Companies', 'GET', '{{base_url}}/companies'),
                        $this->requestItem('Create Company', 'POST', '{{base_url}}/companies', [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'business_name' => 'Empresa Demo SpA',
                                'rut' => '76123456-7',
                                'fantasy_name' => 'Demo',
                                'email' => 'contacto@demo.cl',
                                'phone' => '+56912345678',
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                            'options' => [
                                'raw' => ['language' => 'json'],
                            ],
                        ]),
                        $this->requestItem('Get Company', 'GET', '{{base_url}}/companies/{{company_id}}'),
                        $this->requestItem('Update Company', 'PUT', '{{base_url}}/companies/{{company_id}}', [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'business_name' => 'Empresa Demo Actualizada SpA',
                                'rut' => '76123456-7',
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                            'options' => [
                                'raw' => ['language' => 'json'],
                            ],
                        ]),
                        $this->requestItem('Delete Company', 'DELETE', '{{base_url}}/companies/{{company_id}}'),
                    ],
                ],
                [
                    'name' => 'Leads',
                    'item' => [
                        $this->requestItem('List Leads', 'GET', '{{base_url}}/leads'),
                        $this->requestItem('Create Lead', 'POST', '{{base_url}}/leads', [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'name' => 'Juan Perez',
                                'email' => 'juan@example.com',
                                'phone' => '+56912345678',
                                'lead_status_id' => 1,
                                'source' => 'Web',
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                            'options' => [
                                'raw' => ['language' => 'json'],
                            ],
                        ]),
                        $this->requestItem('Get Lead', 'GET', '{{base_url}}/leads/{{lead_id}}'),
                        $this->requestItem('Update Lead', 'PUT', '{{base_url}}/leads/{{lead_id}}', [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'name' => 'Juan Perez Actualizado',
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                            'options' => [
                                'raw' => ['language' => 'json'],
                            ],
                        ]),
                        $this->requestItem('Delete Lead', 'DELETE', '{{base_url}}/leads/{{lead_id}}'),
                        $this->requestItem('List Lead Statuses', 'GET', '{{base_url}}/lead-statuses'),
                    ],
                ],
            ],
        ];

        return response(
            json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            200,
            [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="crm-api-postman-collection.json"',
            ]
        );
    }

    private function requestItem(string $name, string $method, string $url, ?array $body = null): array
    {
        $request = [
            'name' => $name,
            'request' => [
                'method' => $method,
                'header' => [
                    [
                        'key' => 'Accept',
                        'value' => 'application/json',
                    ],
                ],
                'url' => [
                    'raw' => $url,
                    'host' => ['{{base_url}}'],
                    'path' => array_values(array_filter(explode('/', str_replace('{{base_url}}/', '', $url)))),
                ],
            ],
        ];

        if (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            $request['request']['header'][] = [
                'key' => 'Content-Type',
                'value' => 'application/json',
            ];
            $request['request']['body'] = $body ?? [
                'mode' => 'raw',
                'raw' => '{}',
                'options' => [
                    'raw' => ['language' => 'json'],
                ],
            ];
        }

        return $request;
    }
}
