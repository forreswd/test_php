<?php

class Content extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function insert_article($date,$time,$title,$content,$id,$config,$del){
        $this->db->select('id');
        $id = $this->db->get_where('button', array('bigDate' => $id));
        $id = $id->result_array();
        $this->db->insert('content',array('title' => $title, 'article' => $content, 'time' => $time, 'date' => $date, 'date_id' => $id[0]['id'], 'config' => $config, 'delete' => $del));
    }

    public function insert_button($bigDate,$next,$prev,$content_next,$content_prev,$date){
        $this->db->insert('button',array('bigDate' => $bigDate, 'next' => $next, 'prev' => $prev, 'date' => $date, 'content_next' => $content_next, 'content_prev' => $content_prev));
    }

    public function get_article_by_time($id){
        $date = $this->db->get_where('content', array('time' => $id));
        return $date->result_array();
    }

    public function get_article_by_date_id(){
        $date = $this->get_all_date();
        $result = array();
        foreach($date as $row){
            $value = $this->db->get_where('content', array('date_id' => $row['id']));
            array_push($result,$value->result_array());
        }

        return $result;

    }

    public function delete_article($time){
        $data = $this->db->get_where('content', array('time' => $time));
        $id = $data->result_array();
        $id = $id[0]['id'];
        $this->db->delete('content', array('time' => $time));
        $all = $this->db->get('content');
        $flag = '0';
        foreach ($all->result_array() as $row) {
            if($row['id'] > $id){
                $update = array(
                    'id' => $row['id'] - 1
                );
                $this->db->where('id', $row['id']);
                $this->db->update('content',$update);
                $flag = '1';
            }
        }
        $next = next($all->result_array());
        if($flag == '0'){
            $date = array(
                'time' => $next['time']
            );
        } else {
            $prev = $this->db->get_where('content', array('id' => $id -1));
            $prev = $prev->result_array();
            $date = $prev[0];
        }
        $date['flag'] = $flag;
        return $date;
    }

    public function update_article($time,$title,$article,$now_time){
        $data = array(
            'title' => $title,
            'article' => $article,
            'time'=> $now_time
        );
        $this->db->where('time', $time);
        $this->db->update('content', $data);
    }


    public function get_all_date(){
        $date =$this->db->get('button');
        return $date->result_array();
    }



    public function search_next_article($time){
        $this->db->select('date_id');
        $date_id = $this->db->get_where('content',array('time' => $time));
        $date_id = $date_id->result_array();
        if(!empty($date_id)){
            $this->db->select('id');
            $all = $this->db->get_where('content',array('date_id' => $date_id[0]['date_id']));
            $all = $all->result_array();
            $this->db->select('id');
            $select = $this->db->get_where('content',array('time' => $time));
            $select = $select->result_array();
            if(!$select){
                return 0;
            }
            $end = end($all);
            $flag = false;
            foreach($all as $row){
                if($flag == true){
                    $result =  $this->db->get_where('content',array('id' => $row['id']));
                    $result = $result->result_array();
                    return $result[0];
                }
                if($select[0]['id'] == $row['id'] && $select[0]['id'] != $end['id']){
                    $flag = true;
                }
            }
        }
        return 0;
    }

    public function search_prev_article($time){
        $this->db->select('date_id');
        $date_id = $this->db->get_where('content',array('time' => $time));
        $date_id = $date_id->result_array();
        if(!empty($date_id)){
            $this->db->select('id');
            $all = $this->db->get_where('content',array('date_id' => $date_id[0]['date_id']));
            $all = $all->result_array();
            $this->db->select('id');
            $select = $this->db->get_where('content',array('time' => $time));
            $select = $select->result_array();
            if(!$select){
                return 0;
            }
            $flag = false;
            rsort($all);
            $end = end($all);
            foreach($all as $row){
                if($flag == true){
                    $result =  $this->db->get_where('content',array('id' => $row['id']));
                    $result = $result->result_array();
                    return $result[0];
                }
                if($select[0]['id'] == $row['id'] && $select[0]['id'] != $end['id']){
                    $flag = true;
                }
            }
        }
        return 0;
    }
}