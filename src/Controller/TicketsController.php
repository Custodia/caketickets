<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Tickets Controller
 *
 * @property \App\Model\Table\TicketsTable $Tickets
 */
class TicketsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {

        // If no parameters passed show everything.
        // Else use the status specified
        if ( empty($this->request->params['pass'])) {
            $status = 'All';
        } else {
            $status = $this->request->params['pass'][0];
        }

        // Get title queries from passed parameters.
        $queries = $this->request->params['pass'];
        array_shift($queries);


        // Filter tickets by parameters we got above.
        $this->set('tickets', $this->paginate($this->Tickets
            ->find('byStatus',['status' =>$status])
            ->find('byTitle',['queries' => $queries])));

        $this->set(compact('tickets'));
        $this->set('_serialize', ['tickets']);
    }

    /**
     * View method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => ['Projects', 'Comments', 'Users']
        ]);

        $this->set('ticket', $ticket);
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($projectId)
    {
        $ticket = $this->Tickets->newEntity();
        if ($this->request->is('post')) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->data);
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The ticket has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Tickets->Projects->find(
            'list', ['limit' => 200])
            ->where(['id' => $projectId]);
        $this->set('projectId', $projectId);
        $comments = $this->Tickets->Comments->find('list', ['limit' => 200]);
        $users = $this->Tickets->Users->find('list', ['limit' => 200]);
        $this->set(compact('ticket', 'projects', 'comments', 'users'));
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => ['Projects', 'Comments', 'Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->data);
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The ticket has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Tickets->Projects->find('list', ['limit' => 200]);
        $comments = $this->Tickets->Comments->find('list', ['limit' => 200]);

        // Get the related project id from the projects_tickets table.
        $projectId = $this->Tickets->ProjectsTickets->find()
            ->where(['ticket_id' => $id])
            ->first()['project_id'];
        debug($id);
        debug($projectId);


        //$ProjectsUsers = TableRegistry::get('ProjectsUsers');

        $users = $this->Tickets->Users
            ->find('list', ['limit' => 200, 'projectId' => $projectId])
            ->innerJoinWith(
                'ProjectsUsers', function($q){
                    return $q;//->where(['ProjectsUsers.project_id' => $projectId]);
                }
            );

        debug($users); 

        $this->set(compact('ticket', 'projects', 'comments', 'users'));
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ticket = $this->Tickets->get($id);
        if ($this->Tickets->delete($ticket)) {
            $this->Flash->success(__('The ticket has been deleted.'));
        } else {
            $this->Flash->error(__('The ticket could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
