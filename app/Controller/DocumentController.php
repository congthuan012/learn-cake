<?php
App::uses('AppController', 'Controller');
class DocumentController extends AppController
{

      public $uses = ['Document'];

      public $config = [
            'MAX_SIZE_UPLOAD' => '1M',
            'FILE_TYPE_ALLOW' => ['image/*', 'application/pdf'],
            'UPLOAD_UNITS' => [
                  'values' => [
                        'B' => 1,
                        'KB' => 1024,
                        'MB' => 1024 * 1024,
                        'GB' => 1024 * 1024 * 1024,
                  ],
                  'default' => 'KB'
            ]
      ];

      public function index()
      {
            $uploadUnits = $this->config['UPLOAD_UNITS'];

            $sql = 'FROM documents doc
            LEFT JOIN users ON doc.user_upload = users.id
            WHERE doc.deleted != 1';
            $bindings = [];

            //search

            if (!empty($this->request->query['name'])) {
                  $sql .= ' AND doc.name LIKE ?';
                  $bindings[] = '%' . trim($this->request->query['name']) . '%';
            }

            if (!empty($this->request->query['size_max'])) {
                  $sql .= ' AND doc.size <= ?';
                  $bindings[] = (int)$this->request->query['size_max'] * $uploadUnits['values'][$uploadUnits['default']];
            }

            if (!empty($this->request->query['size_min'])) {
                  $sql .= ' AND doc.size >= ?';
                  $bindings[] = (int)$this->request->query['size_min'] * $uploadUnits['values'][$uploadUnits['default']];
            }

            if (!empty($this->request->query['user_upload'])) {
                  $sql .= ' AND doc.user_upload = ?';
                  $bindings[] = $this->request->query['user_upload'];
            }

            if (!empty($this->request->query['types'])) {
                  $bindings['types'] = $this->request->query['types'];
                  // Xây dựng chuỗi dấu chấm hỏi (?)
                  $questionMarks = implode(',', array_fill(0, count($bindings['types']), '?'));

                  $sql .= ' AND doc.type IN (' . $questionMarks . ')';
                  // Thêm các giá trị bindings vào mảng bindings chính
                  foreach ($bindings['types'] as $type) {
                        $bindings[] = $type;
                  }
                  unset($bindings['types']);
            }
            //search


            //pagination
            $currentPage = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
            $limit = 1;
            if (!empty($this->request->query['limit'])) {
                  $limit = $this->request->query['limit'];
            }
            //pagination

            //get type in database
            $types = $this->Document->find('all', [
                  'group' => 'type',
                  'fields' => 'type',
            ]);

            $allType = [];
            if ($types) {
                  foreach ($types as $item) {
                        $allType[] = $item['Document']['type'];
                  }
            }
            //get type in database

            //get user name 
            $users = $this->Document->query('SELECT users.username,users.id FROM documents INNER JOIN users ON users.id = documents.user_upload GROUP BY users.username');
            $usernames = [];
            if ($users) {
                  foreach ($users as $v) {
                        $usernames[] = [
                              'username' => $v['users']['username'],
                              'id' => $v['users']['id']
                        ];
                  }
            }
            //get user name 

            $totalItems = $this->Document->query('SELECT COUNT(*) as total ' . $sql, $bindings);
            if ($totalItems) {
                  $totalItems = $totalItems[0][0]['total'];
            }
            $totalPages = $totalItems / $limit;

            $offset = ($currentPage - 1) * $limit;

            $sql .= ' LIMIT ' . $limit . ' offset ' . $offset;

            $documents = $this->Document->query('SELECT doc.*, users.username ' . $sql, $bindings);
            $this->set([
                  'documents' => $documents,
                  'currentPage' => $currentPage,
                  'totalPages' => $totalPages,
                  'totalItems' => $totalItems,
                  'allType' => $allType,
                  'uploadUnits' => $uploadUnits,
                  'usernames' => $usernames,
            ]);
      }
}
