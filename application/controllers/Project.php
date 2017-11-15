<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends TM_Controller
{
    public function student_index()
    {
        $this->load->model('Students');

        $project = $this->Students->getProject($_SESSION['id']);
        if ($project === FALSE) {
            addPageNotification('Vous ne faites pas parti d\'un groupe de projet');
            redirect('/');
        }

        $this->_details($project);
    }

    public function teacher_index()
    {
        $this->load->model('Teachers');

        $projects = $this->Teachers->getProjects($_SESSION['id']);

        $this->data = array(
            'projects' => $projects
        );

        $this->show('Projets tuteurés');
    }

    public function teacher_detail($projectId)
    {
        $projectId = (int) htmlspecialchars($projectId);

        if ($projectId === 0) {
            show_404();
        }

        $this->load->model('Projects');
        $this->load->model('Teachers');

        $project = $this->Projects->get($projectId);
        if ($project === FALSE) {
            addPageNotification('Projet introuvable', 'warning');
            redirect('Project');
        }
        if (!$this->Teachers->isTutor($projectId, $_SESSION['id'])) {
            addPageNotification('Vous n\'avez pas accès à ce projet tuteuré', 'danger');
            redirect('Project');
        }

        $this->_details($project);
    }

    private function _details($project)
    {
        $this->load->model('Projects');
        $this->load->model('DateProposals');
        $this->load->model('DateAccepts');

        $this->load->helper('time');

        $members = $this->Projects->getMembers($project->idProject);
        $tutor = $this->Projects->getTutor($project->idProject);
        $lastAppointment = $this->Projects->getLastAppointment($project->idProject);
        $nextAppointment = $this->Projects->getNextAppointment($project->idProject);


        $proposals = array();

        if (!is_null($nextAppointment)) {
            $unsortedProposals = $this->DateProposals->getAll($nextAppointment->idAppointment);
            $unsortedDateAccepts = $this->DateAccepts->getAll($nextAppointment->idAppointment);

            foreach ($unsortedProposals as $proposal) {

                $proposals[$proposal->idDateProposal] = array('proposal' => $proposal, 'refused' => false);
            }

            foreach ($unsortedDateAccepts as $dateAccept) {
                $proposals[$dateAccept->idDateProposal]['proposal']->dateAccepts[$dateAccept->idUser] = $dateAccept;
                $proposals[$dateAccept->idDateProposal]['refused'] = (!is_null($dateAccept->accepted ) && $dateAccept->accepted != 1) ? true : $proposals[$dateAccept->idDateProposal]['refused'];
            }
        }

        $this->data = array(
            'project' => $project,
            'members' => $members,
            'tutor' => $tutor,
            'lastAppointment' => $lastAppointment,
            'nextAppointment' => $nextAppointment,
            'proposals' => $proposals
        );

        $this->setData('view', 'Common/project_detail.php');
        $this->setData('js', 'Common/project_detail');
        $this->show('Projets tuteurés');
    }
}
