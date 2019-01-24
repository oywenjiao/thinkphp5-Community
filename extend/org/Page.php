<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-16
 * Time: 10:39
 */

namespace org;


use think\Paginator;

class Page extends Paginator
{

    //首页
    protected function home() {
        if ($this->currentPage() > 1) {
            return "<a href='" . $this->url(1) . "' title='首页'>首页</a>";
        } else {
            return "<p>首页</p>";
        }
    }

    //上一页
    protected function prev() {
        if ($this->currentPage() > 1) {
            return "<a href='" . $this->url($this->currentPage - 1) . "' title='上一页' class='laypage-prev'>上一页</a>";
        } else {
            return "<p>上一页</p>";
        }
    }

    //下一页
    protected function next() {
        if ($this->hasMore) {
            return "<a href='" . $this->url($this->currentPage + 1) . "' title='下一页' class='laypage-next'>下一页</a>";
        } else {
            return"<p>下一页</p>";
        }
    }

    //尾页
    protected function last() {
        if ($this->hasMore) {
            return "<a href='" . $this->url($this->lastPage) . "' title='尾页' class='laypage-last'>尾页</a>";
        } else {
            return "<p>尾页</p>";
        }
    }

    /**
     * 页码按钮
     * @return string
     */
    protected function getLinks()
    {
        $block = [
            'first' => null,
            'slider' => null,
            'last'  => null
        ];
        $side  = 1;
        $window = $side * 2;
        if ($this->lastPage < $window + 2) {
            $block['first'] = $this->getUrlRange(1, $this->lastPage);
        } elseif ($this->currentPage < $window + 2) {
            $block['first'] = $this->getUrlRange(1, $window + 2);
        } elseif ($this->currentPage > ($this->lastPage - $window)) {
            $block['first'] = $this->getUrlRange(1, 1);
            $block['last'] = $this->getUrlRange($this->lastPage - $window, $this->lastPage);
        } else {
            $block['first'] = $this->getUrlRange(1, 1);
            $block['slider'] = $this->getUrlRange($this->currentPage - $side, $this->currentPage + $side);
        }
        $html = '';
        if (is_array($block['first'])) {
            $html .= $this->getUrlLinks($block['first']);
            $html .= $this->getDots();
        }
        if (is_array($block['slider'])) {
            $html .= $this->getUrlLinks($block['slider']);
            $html .= $this->getDots();
        }
        if (is_array($block['last'])) {
            $html .= $this->getUrlLinks($block['last']);
        }
        if($this->currentPage+$window <= $this->lastPage){
            $html .= $this->last();
        }
        return $html;
    }

    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '%s<div style="text-align: center"><div class="laypage-main">%s %s %s</div></div> ',
                    $this->newCss(),
                    $this->prev(),
                    $this->getLinks(),
                    $this->next()
                );
            } else {
                return sprintf(
                    '%s<div style="text-align: center"><div class="laypage-main">%s %s %s</div></div> ',
                    $this->css(),
                    $this->prev(),
                    $this->getLinks(),
                    $this->next()
                );
            }
        }
    }

    /**
     * 生成一个可点击的按钮
     *
     * @param string $url
     * @param int  $page
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page)
    {
        return '<a href="' . htmlentities($url) . '" title="第"'. $page .'"页" >' . $page . '</a>';
    }

    /**
     * 生成一个禁用的按钮
     *
     * @param string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<p class="pageEllipsis">' . $text . '</p>';
    }

    /**
     * 生成一个激活的按钮
     * 当前页面选中状态
     * @param string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<a class="laypage-curr">' . $text . '</a>';
    }

    /**
     * 生成省略号按钮
     *
     * @return string
     */
    protected function getDots()
    {
        return $this->getDisabledTextWrapper('...');
    }

    /**
     * 批量生成页码按钮.
     *
     * @param array $urls
     * @return string
     */
    protected function getUrlLinks(array $urls)
    {
        $html = '';
        foreach ($urls as $page => $url) {
            $html .= $this->getPageLinkWrapper($url, $page);
        }
        return $html;
    }

    /**
     * 生成普通页码按钮
     *
     * @param string $url
     * @param int  $page
     * @return string
     */
    protected function getPageLinkWrapper($url, $page)
    {
        if ($page == $this->currentPage()) {
            return $this->getActivePageWrapper($page);
        }
        return $this->getAvailablePageWrapper($url, $page);
    }
    
    protected function css(){
        return '<style type="text/css">
            .laypage-main,
            .laypage-main *{display:inline-block; *display:inline; *zoom:1; vertical-align:top;}
            .laypage-main{margin: 20px 0; border: 1px solid #009E94; border-right: none; border-bottom: none; font-size: 0;}
            .laypage-main *{padding: 0 20px; line-height: 36px; border-right: 1px solid #009E94; border-bottom: 1px solid #009E94; font-size: 14px;}
            .laypage-main .laypage-curr{background-color:#009E94; color:#fff;}
            .laypage-main p.pageRemark{
                border-style:none;
                background:none;
                margin-right:0px;
                padding:4px 0px;
                color:#666;
              }
            .laypage-main p.pageRemark b{
                color:red;
            }
        </style>';
    }
}