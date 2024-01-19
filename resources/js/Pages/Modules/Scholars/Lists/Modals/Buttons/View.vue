<template>
    <b-modal v-model="showModal" hide-footer class="v-modal-custom" modal-class="zoomIn" fullscreen>
        <template v-slot:header>
            <div style="border-bottom: 1px solid #ccc; width: 100%;">
                
            <i @click="showModal=false" class="ri-close-circle-fill float-end" style="cursor:pointer; font-size: 40px;"></i>
            <b-row class="mb-3 mt-n1">
                <b-col md>
                    <b-row class="align-items-center g-3">
                        <b-col md="auto">
                            <b-img class="img-thumbnail rounded-circle" style="width: 60px; height: 60px;" alt="img" src="/imagess/avatars/avatar.jpg" data-holder-rendered="true"></b-img>
                        </b-col>
                        <b-col md>
                            <div>
                                <h5 class="fw-semibold">{{user.profile.name}}</h5>
                                <div class="hstack gap-3 flex-wrap">
                                    <div class="text-muted">Spas ID :
                                        <span class="text-body fw-medium">{{user.spas_id}}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Program : 
                                        <span class="text-body fw-medium">{{user.program}}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Subprogram : 
                                        <span class="text-body fw-medium">{{user.subprogram}}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Awarded Year : 
                                        <span class="text-body fw-medium">{{user.awarded_year}}</span>
                                    </div>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
            </div>
        </template>
        <div class="row">
            <div class="col-md-3 mt-n3">
                <table class="table table-bordered">
                    <tbody>
                        <tr style="border-top: none;">
                            <td style="border-left: none; border-top: none;"><span class="fw-semibold fs-12">Scholar Information</span></td>
                        </tr>
                        <tr>
                            <td style="border-left: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex mt-0">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="ri-service-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Status :</p> <span :class="'badge '+user.status.color+' '+user.status.others">{{user.status.name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="ri-building-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">School :</p><h6 class="text-truncate mb-0"> {{user.education.school.name}} </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="mdi mdi-school"></i></div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Course :</p> <h6 class="text-truncate mb-0"> {{user.education.course.name}} </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="ri-calendar-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Level :</p> <h6 class="text-truncate mb-0"> {{user.education.level.name}} <span class="text-muted fs-11">({{user.education.level.others}})</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-left: none;"><span class="fw-semibold fs-12">Location Information</span></td>
                        </tr>
                        <tr>
                            <td style="border-left: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex mt-0">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="ri-map-pin-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Address :</p>  <h6 class="text-truncate mb-0"> {{(user.address.address) ? user.address.address : 'n/a'}} </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="ri-pushpin-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Municipality :</p>  <h6 class="text-truncate mb-0"> {{user.address.municipality.name}} </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="ri-road-map-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Province :</p>  <h6 class="text-truncate mb-0"> {{user.address.province.name}} </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                    <i class="ri-earth-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Region :</p> <h6 class="text-truncate mb-0"> {{user.address.region.name}} </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- <div class="row">
                    <div class="col-md-12 mt-0">
                        <span class="fw-semibold fs-12">Scholar Information</span>
                        <hr class="text-muted"/>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex mt-0">
                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                    <i class="ri-service-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-1 fs-12 text-muted">Status :</p> <span :class="'badge '+user.status.color+' '+user.status.others">{{user.status.name}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex mt-3">
                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                    <i class="ri-building-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-1 fs-12 text-muted">School :</p><h6 class="text-truncate mb-0"> {{user.education.school.name}} </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex mt-3">
                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                    <i class="mdi mdi-school"></i></div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-1 fs-12 text-muted">Course :</p> <h6 class="text-truncate mb-0"> {{user.education.course.name}} </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex mt-3">
                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                    <i class="ri-calendar-fill"></i></div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-1 fs-12 text-muted">Level :</p> <h6 class="text-truncate mb-0"> {{user.education.level.name}} <span class="text-muted fs-11">({{user.education.level.others}})</span></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <hr class="text-muted"/>
                        <span class="fw-semibold fs-12">Location Information</span>
                        <hr class="text-muted"/>
                    </div>
                </div> -->
            </div>
            <div class="col-md-9 mt-n2" >
                <div class="row mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-2 border border-dashed rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                            class="ri-file-copy-2-fill"></i></div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted fs-11 mb-1">Enrollment Status :</p>
                                    <h5 v-if="is_enrolled" class="fs-13 text-success mb-0">Enrolled</h5>
                                    <h5 v-else class="fs-13 text-warning mb-0">Not Enrolled</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-2 border border-dashed rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                            class="ri-money-dollar-circle-fill"></i></div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted fs-11 mb-1">Financial Status :</p>
                                    <h5 class="fs-13 mb-0">Continued</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-2 border border-dashed rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-bank-card-2-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted fs-11 mb-1">Account No.:</p>
                                    <h5 class="fs-13 mb-0">{{user.profile.account_no}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-2 border border-dashed rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded bg-transparent text-primary fs-24">
                                        <i class="ri-wallet-3-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted fs-11 mb-1">Total Received :</p>
                                    <h5 class="fs-13 mb-0">1231</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-n2">
                    <BTabs nav-class="nav-pills nav-custom nav-custom-light" pills small>
                        <BTab title="Enrollments">
                            <hr class="text-muted"/>
                            <table class="table tablez table-bordered table-nowrap align-middle mb-0 mt-2">
                                <thead class="table-light">
                                    <tr class="fs-11">
                                        <th class="text-center" width="5%">#</th>
                                        <th class="text-center" width="20%">Academic Year</th>
                                        <th class="text-center" width="20%">Level</th>
                                        <th class="text-center" width="15%">Grades Submitted</th>
                                        <th class="text-center" width="15%">Benefits Received</th>
                                        <th class="text-center" width="15%">Status</th>
                                        <th></th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <tr class="font-size-11" v-for="(list,index) in enrollments" v-bind:key="index">
                                        <td class="text-center">{{index+1}}</td>
                                        <td class="text-center fs-12">{{list.semester.academic_year}} <span class="text-muted">| {{list.semester.semester.name}}</span></td>
                                        <td class="text-center fs-12">{{list.level}}</td>
                                        <!-- <td class="text-center fs-12">{{checkGrades(list.grades)}}</td> -->
                                        <td class="text-center fs-11 text-muted">No Prospectus</td>
                                        <td class="text-center fs-12">{{checkBenefits(list.benefits)}}</td>
                                        <td class="text-center">
                                            <span v-if="!list.is_completed"><i class="text-danger ri-close-circle-fill" v-b-tooltip.hover title="Grades and Benefits not completed"></i></span>
                                            <span v-else><i class="text-success ri-checkbox-circle-fill" v-b-tooltip.hover title="Grades and Benefits completed"></i></span>
                                        </td>
                                        <td>
                                            <button @click="viewGrades(list)" class="btn btn-sm btn-label me-1" :class="(list.is_grades_completed == 1) ? 'btn-success' : 'btn-light' " type="button">
                                                <div class="btn-content"><i class="ri-eye-line label-icon align-middle fs-12 me-2"></i>Grades</div>
                                            </button>
                                            <button @click="viewBenefits(list)" class="btn btn-sm btn-label" :class="(list.is_benefits_released == 1) ? 'btn-success' : 'btn-light' " type="button">
                                                <div class="btn-content"><i class="ri-eye-line label-icon align-middle fs-12 me-2"></i>Benefits</div>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </BTab>
                        <BTab title="Financial Benefits">
                        
                        </BTab>  
                        <BTab title="Tracers">
                            
                        </BTab>
                        <BTab title="Overview">
                            
                        </BTab>
                    </BTabs>
                </div>
            </div>
        </div>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            user: {
                profile: {},
                status: {},
                education: {
                    school: {},
                    course: {},
                    level: {},
                    info: {}
                },
                addresses: [],
                address: { municipality: '', province: '', region: ''}
            },
            prospectuses: [],
            enrollments: []
        }
    },
    methods: {
        show(user){
            this.user = user;
            this.user.address = this.user.addresses[0];
            this.prospectuses = this.user.education.info.prospectus;
            this.fetch();
            this.showModal = true;
        },
        fetch(){
            axios.get(this.currentUrl+'/scholars/listing', {
                params: {
                    id: this.user.id,
                    option: 'enrollments'
                }
            })
            .then(response => {
                this.enrollments = response.data.data;
            })
            .catch(err => console.log(err));
        },
        checkBenefits(lists){
            const released = lists.reduce((acc, val) => (val.status_id == 13)  ? acc + 1 : acc, 0);
            return released +' of '+lists.length;
        },
        checkGrades(lists){
            const count = lists.reduce((acc, val) => val.grade != null ? acc + 1 : acc, 0);
            return count +' of '+lists.length;
        },
        hide(){
            this.showModal = false;
        },
    }
}
</script>
