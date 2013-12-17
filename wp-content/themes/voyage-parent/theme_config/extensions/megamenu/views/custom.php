<?php
$item = $settings['item_settings'];

$children = $settings['children'];

if(!empty($children)):?>
<div class="megatopmenu">
    <a href="<?php print $item['url']; ?>">
        <span><?php echo $item['item_title'];?></span>
    </a>
    <ul class="submenu-2">
        <?php foreach ($children as $child) :?>
        <li class="menu-level-2">
            <a href="<?php echo $child['url'];?>">
                <span><?php echo $child['title'];?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif;?>