<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class ProjectsController extends AppController
{

    //Individual access rules to projects functions (projects/*).
    public function isAuthorized($user)
    {
        // All registered users can add projects and view the index.
        if (in_array($this->request->action, ['add', 'index'])){
            return true;
        }

        // The owner of an project can edit and delete it.
        if (in_array($this->request->action, ['view', 'edit', 'delete'])){
            $projectId = (int)$this->request->params['pass'][0];
            if ($this->Projects->isOwnedBy($projectId, $user['id'])){
                return true;
            }
        }

        $ProjectsUsers = TableRegistry::get('ProjectsUsers');

        // Check from the ProjectsUsers table if the person trying to access
        // is a moderator of that project.
        if (in_array($this->request->action, ['view'])){
            $projectId = (int)$this->request->params['pass'][0];
            if ($ProjectsUsers->isModeratedBy($projectId, $user['id'])){
                return true;
            }
        }

        // Check from the ProjectsUsers table if the person trying to access
        // is assigned to that project.
        if (in_array($this->request->action, ['view'])){
            $projectId = (int)$this->request->params['pass'][0];
            if ($ProjectsUsers->isAssignedTo($projectId, $user['id'])){
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Tags']
        ];
        $projects = $this->paginate($this->Projects);

        $this->set(compact('projects'));
        $this->set('_serialize', ['projects']);
    }

    /**
     * View method
     *
     * @param string|null $id Project id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => ['Tags', 'Users', 'Tickets']
        ]);

        $this->set('project', $project);
        $this->set('_serialize', ['project']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $project = $this->Projects->newEntity();
        if ($this->request->is('post')) {
            $project = $this->Projects->patchEntity($project, $this->request->data);
            $project->user_id = $this->Auth->user('id');
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            }
        }
        $tags = $this->Projects->Tags->find('list', ['limit' => 200]);
        $users = $this->Projects->Users->find('list', ['limit' => 200]);
        $tickets = $this->Projects->Tickets->find('list', ['limit' => 200]);
        $this->set(compact('project', 'tags', 'users', 'tickets'));
        $this->set('_serialize', ['project']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Project id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => ['Users', 'Tickets']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->data);
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            }
        }
        $tags = $this->Projects->Tags->find('list', ['limit' => 200]);
        $users = $this->Projects->Users->find('list', ['limit' => 200]);
        $tickets = $this->Projects->Tickets->find('list', ['limit' => 200]);
        $this->set(compact('project', 'tags', 'users', 'tickets'));
        $this->set('_serialize', ['project']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Project id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $project = $this->Projects->get($id);
        if ($this->Projects->delete($project)) {
            $this->Flash->success(__('The project has been deleted.'));
        } else {
            $this->Flash->error(__('The project could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
