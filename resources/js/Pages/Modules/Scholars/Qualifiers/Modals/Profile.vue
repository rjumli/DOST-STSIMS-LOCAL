<template>
    <b-modal v-model="showModal" title="View Profile" size="lg" class="v-modal-custom" modal-class="zoomIn" centered>    
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
        <!-- <table class="table table-bordered table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th style="width: 20%;" class="text-center">Status</th>
                    <th style="width: 20%;" class="text-center">Program</th>
                    <th style="width: 20%;" class="text-center">Subprogram</th>
                    <th style="width: 20%;" class="text-center">Qualified Year</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{user.program.name}}</td>
                    <td class="text-center">{{user.program.name}}</td>
                    <td class="text-center">{{user.program.name}}</td>
                    <td class="text-center">{{user.program.name}}</td>
                </tr>
            </tbody>
        </table> -->
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
                    <b-form class="customform mb-2">
                        <div class="row g-2">
                            <div class="col-md-12">
                                <Multiselect class="form-control"
                                placeholder="Select Action"
                                v-model="type" :close-on-select="true" 
                                :searchable="false" 
                                :options="['Update Qualifier','Add Qualifier','Endorse Qualifier']"/>
                            </div>
                            <div class="col-md-12">
                                <div class="row g-2" v-if="type == 'Update Qualifier'">
                                    <div class="col-md-12 mb-2">
                                        <div class="alert alert-info mb-xl-0" role="alert">Updating the qualifier status to "Not Availing" or "Deferment."</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select v-model="status" class="form-select" id="floatingSelect">
                                                <option :value="null" selected>Select Status</option>
                                                <option :value="list.value" v-for="list in statuses" v-bind:key="list.value">{{list.name}}</option>
                                            </select>
                                            <label for="floatingSelect" :class="(form.errors) ? (form.errors.type_id) ? 'text-danger' : '' : ''">Status</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <textarea class="form-control" v-model="reason" rows="3" placeholder="Enter Reason"></textarea>
                                            <label for="floatingSelect" :class="(form.errors) ? (form.errors.reason) ? 'text-danger' : '' : ''">Reason</label>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label>Reason: <span v-if="errors.reason" v-text="errors.reason" class="haveerror"></span></label>
                                            <textarea v-model="reason" class="form-control" maxlength="225" rows="1" placeholder="Reason"></textarea>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
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
            <b-button v-if="type != null" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    props: ['statuses'],
    components: { Multiselect },
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
                subprogram: {}
            },
            form: {},
            type: null,
            status: null,
            reason: null,
            showModal: false
        }
    },
    methods : {
        show(data){
            this.user = data;
            this.showModal = true;
        },
        hide(){
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