<?php
include("connect.php");
$ctable 		= "category";
$ctable1 		= "Category";

if(isset($_REQUEST['searchName']) && $_REQUEST['searchName']!=""){
	$ctable_where .= " (name like '%".$_REQUEST['searchName']."%') AND ";
}

$ctable_where       .= " isDelete=0";
$item_per_page       =  ($_REQUEST["show"] <> "" && is_numeric($_REQUEST["show"]) ) ? intval($_REQUEST["show"]) : 10;

if(isset($_REQUEST["page"]) && $_REQUEST["page"]!=""){
	$page_number = filter_var($_REQUEST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
	if(!is_numeric($page_number))
    {   
        die('Invalid page number!');
    } //incase of invalid page number
}else{
	$page_number = 1; //if there's no page number, set it to 1
}

$get_total_rows = $db->getTotalRecord($ctable,$ctable_where); //hold total records in variable

//break records into pages
$total_pages = ceil($get_total_rows/$item_per_page);

//get starting position to fetch the records
$page_position  = (($page_number-1) * $item_per_page);
$pagiArr        = array($item_per_page, $page_number, $get_total_rows, $total_pages);
$ctable_r       = $db->getData($ctable,"*",$ctable_where,"id DESC limit $page_position, $item_per_page");
?>
<form action="" name="frm" id="frm" method="post">
	<br>
	<?php
		echo $db->getAddButton($ctable,$ctable1);
	?>
    <table id="example" class="table table-striped table-bordered table-colored table-info">
        <thead>
            <tr>
                <th>No.</th>
                <th>Product Category Name</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if(@mysqli_num_rows($ctable_r)>0){
            $count = 0;
            while($ctable_d = @mysqli_fetch_array($ctable_r)){
				$count++;
        	?>
            <tr>
                <td><?php echo $count+$page_position; ?></td>
				<td><?php echo stripslashes($ctable_d['name']); ?></td>
                <td>
					<a class="btn btn-xs btn-icon waves-effect waves-light btn-primary m-b-5" href="<?php echo ADMINURL?>add_<?php echo $ctable; ?>/edit/<?php echo $ctable_d['id']; ?>/" title="Edit"><i class="fa fa-pencil"></i></a>
					
					<a class="btn btn-xs btn-icon waves-effect waves-light btn-danger m-b-5" onClick="del_conf('<?php echo $ctable_d['id']; ?>');" title="Delete">
                        <i class="fa fa-times"></i>
                    </a>
                </td>
            </tr>
        	<?php
            }
        }
        ?>
        </tbody>
    </table>
	<?php 
		$db->getTablePaginationBlock($pagiArr);
		$db->getAddButton($ctable,$ctable1);
	?>
</form>