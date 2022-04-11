<?php defined('_JEXEC') or die; ?>
<?php 
if($this->countModules('left + right') == 0){
  $suff = "wide";
}
if($this->countModules('left + right') > 0){
  $suff = "normal";
}
if($this->countModules('left') > 0 AND $this->countModules('right') == 0){
  $suff = "left";
} 
if($this->countModules('left') == 0 AND $this->countModules('right') > 0){
  $suff = "right";
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <jdoc:include type="head" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
  </head>
  <body>
    <div class="cont">
      <div id="all">
        <div id="container">
          <div id="header">
            <div class="link">
              <a href="http://apostrof.in.ua" target="_blank"></a>
            </div>
            <jdoc:include type="modules" name="header" style="xhtml" />
          </div>
          <div id="wrapper">
            <div id="top">
              <jdoc:include type="modules" name="menu" style="xhtml" />
              <div style="clear:both"></div>
            </div>
            <div id="content" class="<?php echo $suff; ?>">
              <jdoc:include type="modules" name="top" style="xhtml" />
              <jdoc:include type="message" />
              <jdoc:include type="component" />
            </div>
          </div>
          <?php if($this->countModules('left')) : ?>
            <div id="navigation">
              <jdoc:include type="modules" name="left" style="xhtml" />
            </div>
          <?php endif; ?>
          <?php if($this->countModules('right')) : ?>
            <div id="extra">
              <jdoc:include type="modules" name="right" style="xhtml" />
            </div>
          <?php endif; ?>
          <div id="footer">
            <jdoc:include type="modules" name="bottom" style="xhtml" />
          </div>
        </div>  
      </div>
    </div>
    <div class="foot">
      <div class="ft">
        <jdoc:include type="modules" name="footer" style="xhtml" />
      </div>
    </div>
  </body>
</html>