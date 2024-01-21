<template>
    <b-modal v-if="scholar" v-model="showModal" title="View Profile" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <template v-slot:header>
            <div style="border-bottom: 1px solid #ccc; width: 100%;">
                <!-- <i @click="showModal=false" class="ri-close-circle-fill float-end me-3" style="cursor:pointer; font-size: 30px;"></i> -->
                <b-row class="mt-n1 ms-1" style="margin-bottom: 12px;">
                    <b-col md>
                        <b-row class="align-items-center g-1">
                            <b-col md="auto">
                                <b-img class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;" alt="img" src="/imagess/avatars/avatar.jpg" data-holder-rendered="true"></b-img>
                            </b-col>
                            <b-col md>
                                <div class="ms-2 mt-n2">
                                    <h5 class="modal-title fs-15">{{scholar.profile.name}}</h5>
                                    <div class="hstack gap-3 flex-wrap mt-0 mb-n2">
                                        <div class="text-primary"><i class="ri-account-circle-fill align-bottom me-1"></i>
                                            <span class="text-body text-muted fs-12">{{scholar.spas_id }}</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
            </div>
        </template>
        <b-form class="customform mb-n1 mt-0 g-2">
            <div class="row g-1 mt-n1">
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" :value="scholar.education.school.name" class="form-control" readonly>
                        <label>School</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" :value="scholar.education.course.name" class="form-control" readonly>
                        <label>Course</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <Multiselect class="form-control"
                        placeholder="Select Subcourse" label="type" trackBy="type"
                        v-model="subcourse" :close-on-select="true" 
                        :searchable="false" :options="subcourses"/>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="alert alert-warning mb-xl-0 fs-10" role="alert">If there is no available list, please provide the prospectus for the mentioned course.</div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
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
            scholar: { profile:{}, education: { school:{}, course: {}}},
            form: {},
            subcourse: '',
            subcourses: [],
            showModal: false
        }
    },
    methods : {
        set(data){
            this.scholar = data;
            this.showModal = true;
            this.fetchSubcourse();
        },
        create(){
            this.form = this.$inertia.form({
                id: this.scholar.id,
                subcourse_id: this.subcourse,
                type: 'prospectus'
            })
            this.form.put('/scholars/listing/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    // console.log(response.props.flash.data.data);
                    this.showModal = false;
                }
            });
        },
        fetchSubcourse(){
            axios.get(this.currentUrl + '/lists/subcourses/'+this.scholar.education.school.id+'/'+this.scholar.education.course.id)
            .then(response => {
                this.subcourses = response.data.data;
            })
            .catch(err => console.log(err));
        },
        hide(){
            this.$emit('clear',true);
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
  height: 45px;
  min-height: 45px;
  line-height: 1;
  padding-top: 1.300rem;
  font-size: 11px;
}
</style>