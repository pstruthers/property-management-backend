<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Properties Controller
 *
 * @property \App\Model\Table\PropertiesTable $Properties
 */
class PropertiesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $properties = $this->Properties->find()->toArray();

        // Return JSON response directly
        $response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($properties));

        return $response;
    }


    /**
     * View method
     *
     * @param string|null $id Property id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $property = $this->Properties->get($id, contain: []);
        $this->set(compact('property'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->disableAutoLayout();
        $this->viewBuilder()->setClassName('Json');
        $property = $this->Properties->newEmptyEntity();

        $photo = $this->request->getData('photo');
        if ($photo && $photo->getClientFilename()) {
            $filename = uniqid() . '_' . $photo->getClientFilename();
            $targetPath = WWW_ROOT . 'uploads' . DS . $filename;
            $photo->moveTo($targetPath);
            $property->photo = $filename;
        }

        $addressData = $this->request->getData('address');
        $property->address = $addressData['address'] ?? '';
        $property->city = $addressData['city'] ?? '';
        $property->state = $addressData['state'] ?? '';
        $property->zip = $addressData['zip'] ?? '';

        $property->beds = $this->request->getData('beds');
        $property->baths = $this->request->getData('baths');
        $property->sqft = $this->request->getData('sqft');
        $property->price = $this->request->getData('price');

        if ($this->Properties->save($property)) {
            $response = [
                'success' => true,
                'property' => $property
            ];
        } else {
            $response = ['success' => false];
        }

        // Return JSON response directly
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($response));

        return $this->response;
    }

    public function testDb()
    {
        $this->request->allowMethod(['get']);

        try {
            $conn = $this->Properties->getConnection();
            $results = $conn->execute('SHOW TABLES')->fetchAll('assoc');

            // Show tables in JSON
            $this->viewBuilder()->disableAutoLayout();
            $this->viewBuilder()->setClassName('Json');
            $this->set([
                'tables' => $results,
                '_serialize' => ['tables']
            ]);
        } catch (\Exception $e) {
            // Show the actual error in JSON
            $this->viewBuilder()->disableAutoLayout();
            $this->viewBuilder()->setClassName('Json');
            $this->set([
                'error' => $e->getMessage(),
                '_serialize' => ['error']
            ]);
        }
    }


    /**
     * Edit method
     *
     * @param string|null $id Property id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $property = $this->Properties->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $property = $this->Properties->patchEntity($property, $this->request->getData());
            if ($this->Properties->save($property)) {
                $this->Flash->success(__('The property has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The property could not be saved. Please, try again.'));
        }
        $this->set(compact('property'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Property id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $property = $this->Properties->get($id);
        if ($this->Properties->delete($property)) {
            $this->Flash->success(__('The property has been deleted.'));
        } else {
            $this->Flash->error(__('The property could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
