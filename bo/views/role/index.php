
            <link href="views/assets/css/main1.css?v=2" rel="stylesheet">
            <div class="row">
                <div class="col-xs-12">
                    <?php require_once('conf/alertMessage.php'); ?>
                </div>
            </div>

            <div class="row rowMargin">
                <div class="col-md-8"></div>

                <div class="col-md-4">
                    <a class="btn btn-green btn-squared pull-right" role="button" href="?controller=role&action=newr"> <i class="fa fa-plus"></i> <?php echo $lang["new_reg"] ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">


                        <div class="panel-heading">
                            <i class="clip-list"></i> <?php echo $lang["ROLE_LIST"]; ?>
                            <div class="panel-tools">

                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="tableContainer" id="tableprice">
                                <?php require_once('views/layout/tableoption.php'); ?>

                                <div class="table-responsive">
                                    <table class="table_elementsdocument table table-striped  table-hover table-full-width table-condensed" data-url="?controller=role&action=getall">
                                        <thead>
                                            <tr>

                                                <th class="sorting sortingAsc" onclick="ordertable(this);" id="name"><?php echo $lang["name"]; ?></th>
                                                <th class="sorting " onclick="ordertable(this);" id="updatedby"><?php echo $lang["updatedby"]; ?></th>
                                                <th class="sorting " onclick="ordertable(this);" id="updatedat"><?php echo $lang["updatedat"]; ?></th>
                                                <th class="hidden-sm hidden-xs"></th>

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

            <script src="views/assets/plugins/jquery-pagination/jquery.twbsPagination.min.js"></script>
            <script src="views/assets/js/table.js"></script>