<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item):

        $link2edit='index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$item->id;
        
        ?>
        

        <tr class="row<?php echo $i % 2; ?>">
                <td><img src="<?php echo $item->imageurl; ?>" style="width:100px;" /></td>
                <td><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->videosource; ?></a></td>
                <td><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->videoid; ?></a></td>
                <td><?php echo $item->title; ?></td>
                <td><?php echo $item->description; ?></td>
                <td><?php echo $item->lastupdate; ?></td>
                <td><?php
                
                if($item->status==200)
                        echo '<span style="color:green;">Ok</span>';
                elseif($item->status==0)
                        echo '<span style="color:black;">-</span>';
                else
                        echo '<span style="color:red;font-weight:bold;">Error: '.$item->status.'</span>';
                
                ?></td>
                <td><?php echo $item->ordering; ?></td>
        </tr>
				
	
<?php endforeach; ?>
