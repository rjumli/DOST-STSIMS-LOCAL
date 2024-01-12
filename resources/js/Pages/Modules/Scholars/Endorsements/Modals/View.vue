<template>
    <b-modal v-model="showModal" title="View Profile" size="lg" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <template v-slot:header>
            <div style="border-bottom: 1px solid #ccc; width: 100%;">
                <i @click="showModal=false" class="ri-close-circle-fill float-end me-3" style="cursor:pointer; font-size: 30px;"></i>
                <b-row class="mt-n1" style="margin-bottom: 12px;">
                    <b-col md>
                        <b-row class="align-items-center g-1">
                            <b-col md="auto">
                                <b-img class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;" alt="img" src="/imagess/avatars/avatar.jpg" data-holder-rendered="true"></b-img>
                            </b-col>
                            <b-col md>
                                <div class="ms-2 mt-n2">
                                    <h5 class="modal-title fs-15">{{user.profile.name}}</h5>
                                    <div class="hstack gap-3 flex-wrap mt-0 mb-n2">
                                        <div class="text-primary"><i class="ri-account-circle-fill align-bottom me-1"></i>
                                            <span class="text-body text-muted fs-12">{{user.spas_id }}</span>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-primary"><i class="ri-map-pin-2-fill align-bottom me-1"></i>
                                            <span class="text-body text-muted fs-12">
                                                {{(user.address.municipality) ? user.address.municipality.name+',' : ''}}
                                                {{(user.address.province) ? user.address.province.name+',' : ''}}
                                                {{(user.address.region) ? user.address.region.region : ''}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
            </div>
        </template>
        <div class="mb-3 mt-n1">
            <BTabs nav-class="nav-pills nav-custom nav-custom-light" pills small>
                <BTab title="Overview">
                    <hr class="text-muted"/>
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <div class="p-1 border border-dashed rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-0">
                                        <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                                class="ri-file-copy-2-fill"></i></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted fs-11 mb-0">Status :</p>
                                        <span :class="'badge '+user.type.color">{{user.type.name}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-1 border border-dashed rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-0">
                                        <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                                class="mdi mdi-seal-variant"></i></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted fs-11 mb-0">Program :</p>
                                        <h5 class="fs-13 text-success mb-0">{{user.program.name}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-1 border border-dashed rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-0">
                                        <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                                class="ri-award-line"></i></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted fs-11 mb-0">Subprogram :</p>
                                        <h5 class="fs-13 mb-0">{{user.subprogram.name}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-1 border border-dashed rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-0">
                                        <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted fs-11 mb-0">Qualified Year:</p>
                                        <h5 class="fs-13 mb-0">{{user.qualified_year}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="text-muted"/>
                    <b-form class="row customform mb-n1 mt-n2 g-2">
                        
                        <div class="col-lg-12">
                            <div class="form-floating">
                                <input type="text" :value="'DOST - '+user.endorsement.endorsedby.region+' ('+user.endorsement.endorsedby.name+')'" class="form-control" readonly>
                                <label>Endorsed By</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" :value="(!user.endorsement.school.is_main) ? user.endorsement.school.school.name+' - '+user.endorsement.school.campus : user.endorsement.school.school.name" class="form-control" readonly>
                                <label>School</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" :value="user.endorsement.course.name" class="form-control" readonly>
                                <label>Course</label>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-n1">
                            <hr class="text-muted"/>
                        </div>
                    </b-form>
                </BTab>
                <BTab title="Profile">
                    <hr class="text-muted"/>
                    <b-form class="row customform mb-n1 mt-n2 g-2">
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="text" v-model="user.profile.lastname" class="form-control" readonly>
                                <label>Lastname</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="text" v-model="user.profile.firstname" class="form-control" readonly>
                                <label>Firstname</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="text" v-model="user.profile.middlename" class="form-control" readonly>
                                <label>Middlename</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="text" v-model="user.profile.suffix" class="form-control" readonly>
                                <label>Suffix</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" v-model="user.address.region.region" class="form-control" readonly>
                                <label>Region</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" v-model="user.address.province.name" class="form-control" readonly>
                                <label>Province</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" v-model="user.address.municipality.name" class="form-control" readonly>
                                <label>Municipality</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" v-model="user.address.barangay.name" class="form-control" readonly>
                                <label>Barangay</label>
                            </div>
                        </div>
                    </b-form>
                </BTab>  
            </BTabs>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Proceed</b-button>
        </template>
    </b-modal>
    <Confirm @status="hide()" ref="confirm"/>
</template>
<script>
import Confirm from './Confirm.vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    components: { Multiselect, Confirm },
    data(){
        return {
            currentUrl: window.location.origin,
            errors: [],
            user: {
                status: {},
                address: {
                    info: {},
                    region: {},
                    province: {},
                    municipality: {},
                    barangay: {}
                },
                type: {},
                profile: {},
                program: {},
                subprogram: {},
                endorsement: {
                    school: {
                        school:{}
                    },
                    course: {},
                    endorsedby:{}
                }
            },
            form: {},
            school: null,
            course: null,
            showModal: false
        }
    },
    watch : {
        school(newVal){
            this.fetchCourses(this.school);
        },
    },
    methods : {
        show(data){
            this.user = data;
            this.showModal = true;
        },
        create(){
            this.form = this.$inertia.form({
                id: this.user.endorsement.id,
                user: this.user,
                school_id: this.user.endorsement.school.id,
                course_id: this.user.endorsement.course.id,
                account_no: this.account_no,
                type: 'endorsed'
            });
            this.$refs.confirm.show(this.form);
        },
        hide(){
            this.$emit('status',true);
            this.school = null;
            this.course = null;
            this.showModal = false;
        },
    }
}
</script>
<style>
.modal-header {
    padding-top: 15px;
    padding-left: 0px;
    padding-right: 0px;
}
.form-floating > .form-control {
  height: 50px;
  min-height: 50px;
  line-height: 1;
  padding-top: 1.300rem;
  font-size: 12px;
}
</style>