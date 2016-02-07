<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProjectsUsers Controller
 *
 * @property \App\Model\Table\ProjectsUsersTable $ProjectsUsers
 */
class ProjectsUsersController extends AppController
{

    public function toggle()
    {
        debug($this->request->params['pass']);
        $projectId = $this->request->params['pass'][0];
        $userId = $this->request->params['pass'][1];
        $projectsUser = $this->ProjectsUsers->get([$projectId, $userId]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $projectsUser = $this->ProjectsUsers->patchEntity($projectsUser, $this->request->data);
            if ($projectsUser->role === 'User') {
                $projectsUser->role = 'Admin';
            } else {
                $projectsUser->role = 'User';
            }
            if ($this->ProjectsUsers->save($projectsUser)) {
                $this->Flash->success(__('The role has been updated.'));
                return $this->redirect(['controller' => 'Projects', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The projects user could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Projects', 'Users']
        ];
        $projectsUsers = $this->paginate($this->ProjectsUsers);

        $this->set(compact('projectsUsers'));
        $this->set('_serialize', ['projectsUsers']);
    }

    /**
     * View method
     *
     * @param string|null $id Projects User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $projectsUser = $this->ProjectsUsers->get($id, [
            'contain' => ['Projects', 'Users']
        ]);

        $this->set('projectsUser', $projectsUser);
        $this->set('_serialize', ['projectsUser']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $projectsUser = $this->ProjectsUsers->newEntity();
        if ($this->request->is('post')) {
            $projectsUser = $this->ProjectsUsers->patchEntity($projectsUser, $this->request->data);
            if ($this->ProjectsUsers->save($projectsUser)) {
                $this->Flash->success(__('The projects user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The projects user could not be saved. Please, try again.'));
            }
        }
        $projects = $this->ProjectsUsers->Projects->find('list', ['limit' => 200]);
        $users = $this->ProjectsUsers->Users->find('list', ['limit' => 200]);
        $this->set(compact('projectsUser', 'projects', 'users'));
        $this->set('_serialize', ['projectsUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Projects User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $projectsUser = $this->ProjectsUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $projectsUser = $this->ProjectsUsers->patchEntity($projectsUser, $this->request->data);
            if ($this->ProjectsUsers->save($projectsUser)) {
                $this->Flash->success(__('The projects user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The projects user could not be saved. Please, try again.'));
            }
        }
        $projects = $this->ProjectsUsers->Projects->find('list', ['limit' => 200]);
        $users = $this->ProjectsUsers->Users->find('list', ['limit' => 200]);
        $this->set(compact('projectsUser', 'projects', 'users'));
        $this->set('_serialize', ['projectsUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Projects User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $projectsUser = $this->ProjectsUsers->get($id);
        if ($this->ProjectsUsers->delete($projectsUser)) {
            $this->Flash->success(__('The projects user has been deleted.'));
        } else {
            $this->Flash->error(__('The projects user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
