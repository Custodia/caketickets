<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ProjectsUsers Controller
 *
 * @property \App\Model\Table\ProjectsUsersTable $ProjectsUsers
 */
class ProjectsUsersController extends AppController
{

    //Individual access rules for this controller.
    public function isAuthorized($user)
    {
        // Only the project owner can toggle admin status.
        if ($this->request->action === 'toggle'){
            $Projects = TableRegistry::get('Projects');

            $projectId = $this->request->params['pass'][0];
            $userId = $this->Auth->user('id');
            if ($Projects->isOwnedBy($projectId, $userId)){
                return true;
            }
        }

        // Defaults if none of the above qualify.
        return parent::isAuthorized($user);
    }

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

    
}
