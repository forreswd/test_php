<?php

class Test extends CI_Controller
{

    function __construct(){
        parent::__construct();
        $this->load->model('content');
    }
    public function json()
    {

        $data['json'] = array(
            'a' => 'a',
            'b' => 'b'
        );
        $data['json']['c'] = 'c';
       // array_push($data['json'], 'c' = 'c');
        $data['json'] = $this->input($data['json']);
        var_dump($data);

    }

    public function index(){
        header("Content-type: text/html; charset=utf-8");
        $data['title'] = $this->input->post('title');
        $data['content'] = $this->input->post('content');
        $data['hid'] = $this->input->post('hid');
        $data['date'] = $this->content->get_all_date();
        $data['article'] = $this->content->get_article_by_date_id();
        $this->load->view('index',$data);
    }

//    public function t($v1,$v2){
//        $result = array($v1,$v2);
//        json_encode($result);
//        echo $result;
//       //echo $this->input->get('user')+$this->input->get('time');
//    }
    public function echo_page(){
        define("DOCUMDNG_ROOT",$_SERVER['DOCUMENT_ROOT']);
        $path = DOCUMDNG_ROOT.'/test/application/controllers/test.php';
        echo basename($path);
}


    public function create_article($date,$time,$title,$article,$id,$config,$del){
        $this->content->insert_article($date,$time,$title,$article,$id,$config,$del);
//        $date = $this->content->get_article_by_time($v1);
//        echo $date;
    }

    public function create_button($v1,$v2,$v3,$v4,$v5,$v6){
        $this->content->insert_button($v1,$v2,$v3,$v4,$v5,$v6);
//        $date = $this->content->get_article_by_time($v1);
//        echo $date;
    }

    public function update_article($time,$title,$article,$now_time){
        $this->content->update_article($time,$title,$article,$now_time);
    }

    public function delete_article($time){
        $flag = $this->content->delete_article($time);
        echo json_encode($flag);
    }

    public function search_next($time){
        $flag = $this->content->search_next_article($time);
        if($flag != 0){
            echo json_encode($flag);
        } else if($flag == 0){
            echo $flag;
        }
    }
    public function search_prev($time){
        $flag = $this->content->search_prev_article($time);
        if($flag != 0){
            echo json_encode($flag);
        } else if($flag == 0){
            echo $flag;
        }
    }

    public function test2(){
        header("Content-type: text/html; charset=utf-8");
//        $data['date'] = $this->content->get_all_date();
//        var_dump($date);
        $this->content->delete_article('2015-2-13-16-57-7');
//        $this->load->view('test');
//        $this->load->view('index',$data);
    }
    public function input($data)
    {
        $data['test'] = 'c';
        return $data;
    }
}
