<template>
    <b-modal v-model="showModal" title="New Academic Year" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform">
            <div class="row g-2 customerform">
                <div class="col-md-6">
                    <label>Year From: {{ type }}<span v-if="form.errors" v-text="form.errors.academic_year" class="haveerror"></span>
                    </label>
                    <date-picker
                        v-model:value="year"
                        type="year" format="YYYY"
                        lang="en"
                        placeholder="Select Year"
                        :disabled-date="disabledBeforeTodayAndAfterAWeek"
                        :clearable="false"
                        >
                    </date-picker>
                </div>
                <div class="col-md-6" style="margin-top: 18px;">
                   <div class="form-group">
                        <label>Academic Year:</label>
                        <input type="text" class="form-control" v-model="academic_year" readonly style="height: 39px;">
                    </div>
                </div>
                <div class="col-md-12 mt-n2 mb-n2">
                    <hr class="text-muted"/>
                    <b-list-group class="fs-11">
                        <b-list-group-item> Mark all semesters as <span class="fw-semibold text-danger">inactive</span>.</b-list-group-item>
                        <b-list-group-item>Ongoing scholars with an active semester but not enrolled will be marked as having <span class="fw-semibold text-warning">missed enrollment</span>.</b-list-group-item>
                        <b-list-group-item>All semester types for the academic year will be reset to empty.</b-list-group-item>
                        <b-list-group-item>It will automatically update the list from the central server (schools, priority courses, etc.)</b-list-group-item>
                    </b-list-group>
                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow mt-3" role="alert">
                        <i class="ri-alert-line label-icon"></i><span class="fs-11 fw-semibold">This action will update the academic year within the system.</span>
                    </div>
                </div>
                <div class="col-md-12 mt-n2">
                    <hr class="text-muted mb-2"/>
                    <p class="fs-12 text-muted float-end mb-1">API Connection : <span v-if="status" class="fw-semibold text-success">Connected</span><span v-else class="fw-bold text-danger">Not Connected</span></p>
                    <p class="fs-12 text-muted float-right mb-1">Please type the key <span class="text-dark fw-semibold fs-12">{{key}}</span> to confirm</p>
                    <div class="col-md-12">
                    <div class="form-group">
                            <input type="text" class="form-control text-center" v-model="final">
                        </div>
                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button v-if="final == key" @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
export default {
    components : { DatePicker },
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            year: '',
            y: '',
            final: '',
            form: {},
            key: '',
            status: '',
            editable: false,
        }
    },
    computed : {
        academic_year : function(){
            if(this.year != ''){
                this.y = new Date(this.year).getFullYear();
                return new Date(this.year).getFullYear()+'-'+ (Number(new Date(this.year).getFullYear())+1);
            }else{
                return '';
            }
        },
    },
    methods : {
        show() {
            this.key = Math.random().toString(36).slice(2);
            this.checkApi();
            this.showModal = true;
        },
        checkApi() {
            axios.get(this.currentUrl + '/sync/check')
            .then(response => {
                this.status = response.data.status;
            })
            .catch(err => {
                if(err.response.status == 404){
                    this.status = err.response.status;
                }
            });
        },
        create(){
            this.form = this.$inertia.form({
                academic_year: this.academic_year,
                year: this.y,
                type: 'year'
            })
            this.form.put('/settings/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        hide(){
            this.showModal = false;
            this.final = '';
            this.year = '';
        }
    }
}
</script>
