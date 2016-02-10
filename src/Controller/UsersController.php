<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    public function isAuthorized($user)
    {
        if (in_array($this->request->action, ['edit', 'delete'])){
            $userId = (int)$this->request->params['pass'][0];
            // Users can edit and delete their own profiles.
            if ($userId === $user['id']){
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add','logout','index']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Projects', 'Tickets']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user->role = 'User';
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['controller' => 'Projects', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Users->Projects->find('list', ['limit' => 200]);
        $tickets = $this->Users->Tickets->find('list', ['limit' => 200]);
        $this->set(compact('user', 'projects', 'tickets'));
        $this->set('user', $user);
    }

    public function adminAdd()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Users->Projects->find('list', ['limit' => 200]);
        $tickets = $this->Users->Tickets->find('list', ['limit' => 200]);
        $this->set(compact('user', 'projects', 'tickets'));
        $this->set('user', $user);
    }

    public function adminEdit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Projects', 'Tickets']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Users->Projects->find('list', ['limit' => 200]);
        $tickets = $this->Users->Tickets->find('list', ['limit' => 200]);
        $this->set(compact('user', 'projects', 'tickets'));
        $this->set('user', $user);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Projects', 'Tickets']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['controller' => 'Projects', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Users->Projects->find('list', ['limit' => 200]);
        $tickets = $this->Users->Tickets->find('list', ['limit' => 200]);
        $this->set(compact('user', 'projects', 'tickets'));
        $this->set('user', $user);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     *
     * Checks if username and password match
     * and if they do tells Auth what user is logged in.
     * If they don't displays a error message.
     */
    public function login() 
    {
        if($this->request->is('post')){
            $user = $this->Auth->identify();
            if($user){
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again.'));
        }
    }

    /**
     * Logout method.
     *
     * Always redirects user to Auths logout url.
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
