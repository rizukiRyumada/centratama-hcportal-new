                    <!-- Tab form Organization Chart -->
                    <div class="tab-pane fade" id="custom-tabs-attachment" role="tabpanel" aria-labelledby="custom-tabs-attachment-tab">
                        <!-- jquery upload area -->
                        <?php if($is_edit == 1): ?>
                            <div id="fileuploader">Upload</div>
                        <?php endif; ?>

                        <div class="row mb-2">
                            <div class="col">
                                <p class="m-0">Total files uploaded: <span id="file_counter"></span></p>
                            </div>
                        </div>

                        <!-- table list of files -->
                        <table id="files_table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Type</th>
                                    <th>Uploaded</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="list_files">
                            </tbody>
                            
                        </table>
                    </div><!-- /Tab form Organization Chart -->