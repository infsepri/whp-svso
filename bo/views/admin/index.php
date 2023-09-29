<link href="views/assets/css/main1.css?v=2" rel="stylesheet">
<div class="row">
    <div class="col-xs-12">
        <?php require_once('conf/alertMessage.php'); ?>
    </div>
</div>
<?php
$anchor = isset($_GET['tab']) ? $_GET['tab'] : 'panel_overview';
  
?>
<div class="row rowMargin">
    <div class="col-md-8"></div>

    <div class="col-md-4">
        <a class="btn btn-green btn-squared pull-right" role="button" href="?controller=admin&action=newad"> <i class="fa fa-plus"></i> <?php echo $lang["new_reg"] ?></a>
    </div>
</div>


<div class="tabbable">
    <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
        <li class="<?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview') ? 'active' : ''; ?> ">
            <a data-toggle="tab" href="#panel_overview">
                <?php echo $lang["MENU_MANAGEMENT_ADMIN_LIST"]; ?>
            </a>
        </li>
        <?php   if (isset($checkusersepri) && !empty($checkusersepri)) { ?>
            <?php   if (isset($doctor) && !empty($doctor)) { ?>
            
        <li class="<?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview1') ? 'active' : ''; ?> ">
            <a data-toggle="tab" href="#panel_overview1">
                <?php echo $lang["MENU_MANAGEMENT_DOCTOR_LIST"]; ?>
            </a>
        </li>
        <?php } ?>
        <?php   if (isset($nurse) && !empty($nurse)) { ?>
        <li class="<?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview3') ? 'active' : ''; ?> ">
            <a data-toggle="tab" href="#panel_overview3">
                <?php echo $lang["MENU_MANAGEMENT_NURSE_LIST"]; ?>
            </a>
        </li>
        <?php } ?>

        <?php   if (isset($physiotherapist) && !empty($physiotherapist)) { ?>
        <li class="<?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview2') ? 'active' : ''; ?> ">
            <a data-toggle="tab" href="#panel_overview2">
                <?php echo $lang["MENU_MANAGEMENT_PHYSIOTHERAPIST_LIST"]; ?>
            </a>
        </li>
        <?php } ?>
        <?php   if (isset($tsecurity) && !empty($tsecurity)) { ?>
        <li class="<?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview4') ? 'active' : ''; ?> ">
            <a data-toggle="tab" href="#panel_overview4">
                <?php echo $lang["technical_security"]; ?>
            </a>
        </li>
        <?php } ?>
        



        <?php } ?>
    </ul>
    <div class="tab-content">



    <div id="panel_overview" class="tab-pane   <?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview') ? 'in active' : ''; ?> ">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="tableContainer" id="tableAdmin">
                            <?php require('views/layout/tableoption.php'); ?>
                            <div class="table-responsive">
                                <table class="table_elementsdocument table table-striped  table-hover  table-full-width table-condensed" data-url="?controller=admin&action=getall">
                                    <thead>
                                        <tr>
                                            <th class="sorting  hidden-sm hidden-xs">#</th>
                                            <th class="sorting  " onclick="ordertable(this);" id="photo"><?php echo $lang["photo"]; ?></th>
                                            <th class="sorting sortingAsc" onclick="ordertable(this);" id="entity.name"><?php echo $lang["name"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="numberhelth"><?php echo $lang["numberhelth"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="email"><?php echo $lang["email"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.mobilephone"><?php echo $lang["mobilephone"]; ?></th>



                                            <th colspan="3" class="center"><?php echo $lang["MENU_NOTIFICATION"]; ?></th>


                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedby"><?php echo $lang["updatedby"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedat"><?php echo $lang["updatedat"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="state"><?php echo $lang["state"]; ?></th>

                                        </tr>
                                    </thead>
                                    <tbody class="tablebody">

                                    </tbody>
                                </table>
                            </div>
                            <nav class="pull-left">
                                <div class="totalContainer"></div>
                            </nav>
                            <nav class="pull-right">
                                <ul class="pagination-sm pagination pagination-demo"> </ul>
                                <div class="page-content" style="display:none;">1</div>
                            </nav>
                            <br />
                            <div class="legContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- panel_overview -->
<?php   if (isset($checkusersepri) && !empty($checkusersepri)) { ?>
    <div id="panel_overview1" class="tab-pane   <?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview1') ? 'in active' : ''; ?> ">
    <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="tableContainer" id="tabledoctor">
                            <?php require('views/layout/tableoption.php'); ?>
                            <div class="table-responsive">
                                <table class="table_elementsdocument table table-striped  table-hover  table-full-width table-condensed" data-url="?controller=doctor&action=getall">
                                    <thead>
                                        <tr>
                                            <th class="sorting  hidden-sm hidden-xs">#</th>
                                            <th class="sorting  " onclick="ordertable(this);" id="photo"><?php echo $lang["photo"]; ?></th>
                                            <th class="sorting sortingAsc" onclick="ordertable(this);" id="entity.name"><?php echo $lang["name"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="numberhelth"><?php echo $lang["numberhelth"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="email"><?php echo $lang["email"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.mobilephone"><?php echo $lang["mobilephone"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedby"><?php echo $lang["updatedby"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedat"><?php echo $lang["updatedat"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="state"><?php echo $lang["state"]; ?></th>

                                        </tr>
                                    </thead>
                                    <tbody class="tablebody">

                                    </tbody>
                                </table>
                            </div>
                            <nav class="pull-left">
                                <div class="totalContainer"></div>
                            </nav>
                            <nav class="pull-right">
                                <ul class="pagination-sm pagination pagination-demo"> </ul>
                                <div class="page-content" style="display:none;">1</div>
                            </nav>
                            <br />
                            <div class="legContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- panel_overview1 -->


    <div id="panel_overview3" class="tab-pane   <?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview3') ? 'in active' : ''; ?> ">
    <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="tableContainer" id="tablenurse">
                            <?php require('views/layout/tableoption.php'); ?>
                            <div class="table-responsive">
                                <table class="table_elementsdocument table table-striped  table-hover  table-full-width table-condensed" data-url="?controller=nurse&action=getall">
                                    <thead>
                                        <tr>
                                            <th class="sorting  hidden-sm hidden-xs">#</th>
                                            <th class="sorting  " onclick="ordertable(this);" id="photo"><?php echo $lang["photo"]; ?></th>
                                            <th class="sorting sortingAsc" onclick="ordertable(this);" id="entity.name"><?php echo $lang["name"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="numberhelth"><?php echo $lang["numberhelth"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="email"><?php echo $lang["email"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.mobilephone"><?php echo $lang["mobilephone"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedby"><?php echo $lang["updatedby"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedat"><?php echo $lang["updatedat"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="state"><?php echo $lang["state"]; ?></th>

                                        </tr>
                                    </thead>
                                    <tbody class="tablebody">

                                    </tbody>
                                </table>
                            </div>
                            <nav class="pull-left">
                                <div class="totalContainer"></div>
                            </nav>
                            <nav class="pull-right">
                                <ul class="pagination-sm pagination pagination-demo"> </ul>
                                <div class="page-content" style="display:none;">1</div>
                            </nav>
                            <br />
                            <div class="legContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- panel_overview1 -->


    <div id="panel_overview2" class="tab-pane   <?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview2') ? 'in active' : ''; ?> ">
    <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="tableContainer" id="tablephysiotherapist">
                            <?php require('views/layout/tableoption.php'); ?>
                            <div class="table-responsive">
                                <table class="table_elementsdocument table table-striped  table-hover  table-full-width table-condensed" data-url="?controller=physiotherapist&action=getall">
                                    <thead>
                                        <tr>
                                            <th class="sorting  hidden-sm hidden-xs">#</th>
                                            <th class="sorting  " onclick="ordertable(this);" id="photo"><?php echo $lang["photo"]; ?></th>
                                            <th class="sorting sortingAsc" onclick="ordertable(this);" id="entity.name"><?php echo $lang["name"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="numberhelth"><?php echo $lang["numberhelth"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="email"><?php echo $lang["email"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.mobilephone"><?php echo $lang["mobilephone"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedby"><?php echo $lang["updatedby"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedat"><?php echo $lang["updatedat"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="state"><?php echo $lang["state"]; ?></th>

                                        </tr>
                                    </thead>
                                    <tbody class="tablebody">

                                    </tbody>
                                </table>
                            </div>
                            <nav class="pull-left">
                                <div class="totalContainer"></div>
                            </nav>
                            <nav class="pull-right">
                                <ul class="pagination-sm pagination pagination-demo"> </ul>
                                <div class="page-content" style="display:none;">1</div>
                            </nav>
                            <br />
                            <div class="legContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- panel_overview1 -->


    <div id="panel_overview4" class="tab-pane   <?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview4') ? 'in active' : ''; ?> ">
    <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="tableContainer" id="tabletsecurity">
                            <?php require('views/layout/tableoption.php'); ?>
                            <div class="table-responsive">
                                <table class="table_elementsdocument table table-striped  table-hover  table-full-width table-condensed" data-url="?controller=tsecurity&action=getall">
                                    <thead>
                                        <tr>
                                            <th class="sorting  hidden-sm hidden-xs">#</th>
                                            <th class="sorting  " onclick="ordertable(this);" id="photo"><?php echo $lang["photo"]; ?></th>
                                            <th class="sorting sortingAsc" onclick="ordertable(this);" id="nameentity"><?php echo $lang["name"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="numberhelth"><?php echo $lang["numberhelth"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="email"><?php echo $lang["email"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.mobilephone"><?php echo $lang["mobilephone"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedby"><?php echo $lang["updatedby"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="entity.updatedat"><?php echo $lang["updatedat"]; ?></th>
                                            <th class="sorting" onclick="ordertable(this);" id="state"><?php echo $lang["state"]; ?></th>

                                        </tr>
                                    </thead>
                                    <tbody class="tablebody">

                                    </tbody>
                                </table>
                            </div>
                            <nav class="pull-left">
                                <div class="totalContainer"></div>
                            </nav>
                            <nav class="pull-right">
                                <ul class="pagination-sm pagination pagination-demo"> </ul>
                                <div class="page-content" style="display:none;">1</div>
                            </nav>
                            <br />
                            <div class="legContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- panel_overview1 -->

    </div>

    <?php } ?>
</div><!-- content -->








<script src="views/assets/plugins/jquery-pagination/jquery.twbsPagination.min.js"></script>
<script src="views/assets/js/table.js"></script>