<?php
/**
 * 站点ROOTER端
 * (即将开发游客操作记录（访问位置、作何操作），需要新表，不能用tourist表)
 * @author Ltre
 * @since 2015-1-2
 */
class RootDo extends TemplateDo {
    
    //ROOTER端框首页
    function start(){
        $this->tpl();
    }
    
    //非ROOTER页面：游客当天最初访问记录(Tourist表，IP有唯一索引)
    function tourist($page=1, $persize=10, $pagescope=20){
        $T = DIModelUtil::supertable('Tourist');
        $ts = $T->select('', '', 'vtime DESC,id DESC', array($page, $persize, $pagescope));
        empty($ts) && $ts = array();
        foreach ($ts as &$t) {
            $t->vtime = date('Y-m-d H:i:s', $t->vtime);
        }
        $this->tourists = $ts;
        $this->pager = $T->page;
        $this->persize = $persize;
        $this->pagescope = $pagescope;
        $this->tpl();
    }
    
    //ROOTER页面：所有用户(包括游客)访问记录(Rootist表，IP没有唯一索引)
    function rootist($page = 1, $persize = 10, $pagescope = 20){
        $R = DIModelUtil::supertable('Rootist');
        $rs = $R->select('', '', 'vtime DESC, ip DESC', array($page, $persize, $pagescope));//注意，这里的ip DESC没错，就按IP二级排序，不是上面的id DESC
        empty($rs) && $rs = array();
        foreach ($rs as &$r) {
            $r->vtime = date('Y-m-d H:i:s', $r->vtime);
        }
        $this->rootists = $rs;
        $this->pager = $R->page;
        $this->persize = $persize;
        $this->pagescope = $pagescope;
        $this->tpl();
    }
    
    //动态加载ROOTER公共模板内容
    protected function tpl(){
        //存储IP到模板
        import('net/ip');
        $this->client_ip = Api_ip::get_client_ip();
//$this->client_ip = '119.129.211.107';//test
        parent::tpl();
    }
    
}