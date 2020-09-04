<!-- banner -->
<div class="row mb-3">
    <div class="col-md-2 d-md-inline-block d-none">
        <img src="<?= base_url('/assets/img/illustration/ptk/thingking-new-employee.svg'); ?>" alt="" class="responsive-image">
    </div>
    <div class="
    <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $my_hirarki == "N-1" || $my_hirarki == "N-2"): ?>
        col-md-6
    <?php else: ?>
        col-md-10
    <?php endif; ?>
    ">
        <div class="row h-100">
            <div class="col align-self-center">
                <p class="text m-0">Employee Requisition Form digunakan untuk mengajukan tenaga kerja baru, dengan melalui beberapa tahap approval.</p>
            </div>
        </div>
    </div>
    <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $my_hirarki == "N-1" || $my_hirarki == "N-2"): ?>
        <div class="col-md-4 py-2">
            <div class="row h-100">
                <div class="col align-self-center text-center">
                    <a href="<?= base_url('ptk/createNewForm'); ?>" class="w-100 btn btn-success">
                        <div class="row h-100">
                            <div class="col-auto align-self-center text-center">
                                <img src="<?= base_url('/assets/img/illustration/add-document.svg'); ?>" alt="add-document" class="img-lg">
                            </div>
                            <div class="col align-self-center text-center">
                                <p class="text m-0">Create New Employee Requisition Form</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- main tabs -->
<div class="row">
    <div class="col">
        <div class="card card-success card-tabs">
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <div class="card-header px-0 pb-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link ptk_tableTrigger active" id="custom-tabs-four-home-tab" data-toggle="pill" data-status="1" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Active</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link ptk_tableTrigger" id="custom-tabs-four-profile-tab" data-toggle="pill" data-status="0" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Inactive</a>
                  </li>
                  <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link ptk_tableTrigger" id="custom-tabs-four-messages-tab" data-toggle="pill" data-status="2" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">All Data</a>
                    </li>
                  <?php endif; ?>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <table id="table_indexPTK" class="table table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>Date</th>
                            <th>Division</th>
                            <th>Department</th>
                            <!-- <th>Subject</th> -->
                            <th>Status</th>
                            <th style="width: 98px;">View Details</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Status Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- The time line -->
                        <div class="timeline">
                            <!-- timeline time label -->
                            <div class="time-label">
                                <span class="bg-red">10 Feb. 2014</span>
                            </div>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-envelope bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                    <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>
                                    
                                    <div class="timeline-body">
                                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                        quora plaxo ideeli hulu weebly balihoo...
                                    </div>
                                    <div class="timeline-footer">
                                        <a class="btn btn-primary btn-sm">Read more</a>
                                        <a class="btn btn-danger btn-sm">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-user bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> 5 mins ago</span>
                                    <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-comments bg-yellow"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> 27 mins ago</span>
                                    <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                                    <div class="timeline-body">
                                        Take me to your leader!
                                        Switzerland is small and neutral!
                                        We are more like Germany, ambitious and misunderstood!
                                    </div>
                                    <div class="timeline-footer">
                                        <a class="btn btn-warning btn-sm">View comment</a>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline time label -->
                            <div class="time-label">
                                <span class="bg-green">3 Jan. 2014</span>
                            </div>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <i class="fa fa-camera bg-purple"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> 2 days ago</span>
                                    <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>
                                    <div class="timeline-body">
                                        <img src="http://placehold.it/150x100" alt="...">
                                        <img src="http://placehold.it/150x100" alt="...">
                                        <img src="http://placehold.it/150x100" alt="...">
                                        <img src="http://placehold.it/150x100" alt="...">
                                        <img src="http://placehold.it/150x100" alt="...">
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-video bg-maroon"></i>
                                
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> 5 days ago</span>
                                    
                                    <h3 class="timeline-header"><a href="#">Mr. Doe</a> shared a video</h3>
                                    
                                    <div class="timeline-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/tMWkeBIohBs" allowfullscreen="" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                    <div class="timeline-footer">
                                        <a href="#" class="btn btn-sm bg-maroon">See comments</a>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>