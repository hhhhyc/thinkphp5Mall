<?php

namespace app\bis\controller;

class Deal extends Base {
    public function index(){
        $bisid=$this->getLoginUser()->bis_id;
        $deal = model('Deal')->getDealByBisId($bisid);
        return $this->fetch('',[
            'deal'=>$deal,
        ]);
    }

    public function add(){
        $bisid=$this->getLoginUser()->bis_id;
        if(request()->isPost()){
            $data=input('post.');
            $location = model('Bislocation')->get($data['location_ids'][0]);
            $deals=[
              'bis_id'=>$bisid,
              'name'=>$data['name'],
              'image'=>$data['image'],
                'category_id'=>$data['category_id'],
                'se_category_id'=>empty($data['se_category_id'])?'':implode(',',$data['se_category_id']),
                'city_id'=>$data['city_id'],
                'location_ids'=>empty($data['location_ids'])?'':implode(',',$data['location_ids']),
                'start_time'=>strtotime($data['start_time']),
                'end_time'=>strtotime($data['end_time']),
                'total_count'=>$data['total_count'],
                'origin_price'=>$data['origin_price'],
                'current_price'=>$data['current_price'],
                'coupons_begin_time'=>strtotime($data['coupons_begin_time']),
                'coupons_end_time'=>strtotime($data['coupons_end_time']),
                'notes'=>$data['notes'],
                'description'=>$data['description'],
                'bis_account_id'=>$this->getLoginUser()->id,
//               'xpoint'=>$bislocation->xpoint,
//               'ypoint'=>$bislocation->ypoint,

            ];
           $id = model('Deal')->add($deals);
           if($id){
               $this->success('添加成功',url('deal/Index'));
           }
           else{
               $this->error('添加失败');
           }
        }

        //获取一级城市
        $citys=model('City')->getNormalCityByParentId();
        //获取一级栏目数据
        $category=model('Category')->getNormalCategoryByParentId();
        return $this->fetch('',[
            'citys'=>$citys,
            'category'=>$category,
            'bislocation'=>model('Bislocation')->getLocationByBisId($bisid),
        ]);
    }
}