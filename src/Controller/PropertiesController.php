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
        $properties = $this->Properties->find()->all();

        $scheme = $this->request->getUri()->getScheme();
        $host   = $this->request->getUri()->getHost();
        $port   = $this->request->getUri()->getPort();

        $data = [];
        foreach ($properties as $property) {
            $photoUrl = $property->photo
                ? $scheme . '://' . $host . ($port ? ":$port" : "") . '/webroot/uploads/' . $property->photo
                : null;

            $data[] = [
                'id'      => $property->id,
                'address' => $property->address,
                'city'    => $property->city,
                'state'   => $property->state,
                'zip'     => $property->zip,
                'beds'    => $property->beds,
                'baths'   => $property->baths,
                'sqft'    => $property->sqft,
                'price'   => $property->price,
                'photo'   => $photoUrl,
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($data));
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

        // Disable view/template rendering
        $this->viewBuilder()->disableAutoLayout();
        $this->viewBuilder()->setClassName('Json');

        $property = $this->Properties->newEmptyEntity();

        // Handle uploaded photo
        $photo = $this->request->getData('photo');
        if ($photo && $photo->getClientFilename()) {
            $filename = uniqid() . '_' . $photo->getClientFilename();
            $targetPath = WWW_ROOT . 'uploads' . DS . $filename;
            try {
                $photo->moveTo($targetPath);
                $property->photo = $filename;
            } catch (\Exception $e) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'error' => 'Failed to upload photo: ' . $e->getMessage()
                    ]));
            }
        }

        // Handle nested address object
        $property->address = $this->request->getData('address.address') ?? '';
        $property->city    = $this->request->getData('address.city') ?? '';
        $property->state   = $this->request->getData('address.state') ?? '';
        $property->zip     = $this->request->getData('address.zip') ?? '';

        // Other property fields
        $property->beds  = $this->request->getData('beds') ?? null;
        $property->baths = $this->request->getData('baths') ?? null;
        $property->sqft  = $this->request->getData('sqft') ?? null;
        $property->price = $this->request->getData('price') ?? null;

        // Attempt to save
        if ($this->Properties->save($property)) {
            $scheme = $this->request->getUri()->getScheme(); // http or https
            $host   = $this->request->getUri()->getHost();   // your backend domain
            $port   = $this->request->getUri()->getPort();

            $photoUrl = $property->photo
                ? $scheme . '://' . $host . ($port ? ":$port" : "") . '/uploads/' . $property->photo
                : null;

            $response = [
                'success'  => true,
                'property' => [
                    'id'        => $property->id,
                    'address'   => $property->address,
                    'city'      => $property->city,
                    'state'     => $property->state,
                    'zip'       => $property->zip,
                    'beds'      => $property->beds,
                    'baths'     => $property->baths,
                    'sqft'      => $property->sqft,
                    'price'     => $property->price,
                    'photo' => $photoUrl,
                ],
            ];
        } else {
            $errors = $property->getErrors();
            $response = [
                'success' => false,
                'errors'  => $errors
            ];
        }


        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($response));
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
