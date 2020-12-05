<?php
function getcategoryparent() {
  if (Yii::app()->hasModule('blog')) {
    $dependency = new CDbCacheDependency('SELECT max(updatedate) FROM category');
    $sql = "select categoryid,title,description,slug
      from category
      where recordstatus = 1 and parentid is null";
    return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
  } else {
    return null;
  }
}
function getcategorychild($parentid) {
  if (Yii::app()->hasModule('blog')) {
    $dependency = new CDbCacheDependency('SELECT max(updatedate) FROM category where parentid = '.$parentid);
    $sql = 'select categoryid,title,description,slug
      from category
      where recordstatus = 1 and parentid = '.$parentid;
    return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
  } else {
    return null;
  }
}
function hascategorychild($parentid) {
  if (Yii::app()->hasModule('blog')) {
    $dependency = new CDbCacheDependency('SELECT max(updatedate) FROM category where parentid = '.$parentid);
    $sql = 'select ifnull(count(1),0)
      from category
      where recordstatus = 1 and parentid = '.$parentid;
    return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryScalar();
  } else {
    return 0;
  }
}
function gettagcategory($categoryid) {
  $splittag	 = explode(',', $categoryid);
  $tagku		 = "";
  foreach ($splittag as $tag) {
    $sql				 = "select slug,title ".
      " from category a ".
      " where categoryid = ".$tag;
    $dependency	 = new CDbCacheDependency('SELECT updatedate FROM category');
    $tagg				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
    if ($tagku == "") {
      $tagku = "<a href=".Yii::app()->createUrl('blog/category/read/'.$tagg['slug']).">".$tagg['title']."</a>";
    } else {
      $tagku += ",".$tagg['title'];
    }
  }
  return $tagku;
}
function getallcategory() {
  $dependency = new CDbCacheDependency('SELECT max(updatedate) FROM category');
  $sql = "select categoryid,title,description,slug
    from category
    where recordstatus = 1";
  return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
}
function getalltag() {
  $sql = "select slug
    from category
    where recordstatus = 1
    union 
    select slug
    from post
    where recordstatus = 1
    ";
  return Yii::app()->db->createCommand($sql)->queryAll();
}
function getslideshow($category='',$limit=0,$latest=1){
  $dependency = new CDbCacheDependency('SELECT max(updatedate) FROM slideshow');
  $sql = "select a.slidetitle,a.slidedesc,a.slideurl,a.slidepic
    from slideshow a 
    left join slideshowcategory b on b.slideshowid = a.slideshowid 
    left join category c on c.categoryid = b.categoryid ";
  if ($category != '') {
    $sql .= " where coalesce(c.title,'') = '".$category."'";
  }
  if ($latest == 1) {
    $sql .= " order by a.updatedate desc";
  }
  if ($limit > 0) {
    $sql .= " limit ".$limit;
  }
  return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
}
function getpost($category='',$limit=0,$latest=0) {
  $dependency = new CDbCacheDependency('SELECT max(postupdate) FROM post');
  $sql = "select a.postpic,a.title,a.description,a.metatag,a.slug
    from post a
    left join postcategory b on b.postid = a.postid 
    left join category c on c.categoryid = b.categoryid ";
  if ($category != '') {
    $sql .= " where coalesce(c.title,'') = '".$category."'";
  };
  if ($latest == 1) {
    $sql .= " order by a.postupdate desc ";
  }
  if ($limit > 0) {
    $sql .= " limit ".$limit;
  }
  return Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();
}