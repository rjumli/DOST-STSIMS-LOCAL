<template>
    <b-modal v-model="showModal" :title="(selected.status) ?'View Semester':'Create Semester'" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2" v-if="!selected.status">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="form-floating">
                        <input type="text" :value="selected.name" class="form-control" readonly>
                        <label>School</label>
                    </div>
                </div>
                <div class="mt-1" :class="(month)?'col-md-6':'col-md-12'">
                    <div class="form-floating">
                        <input type="text" :value="settings.academic_year+' - '+name" class="form-control" readonly>
                        <label>Academic Year</label>
                    </div>
                </div>
                 <div class="col-md-6 mt-1" v-if="month">
                    <div class="form-floating">
                        <input type="text" :value="month" class="form-control" readonly>
                        <label>Month Range</label>
                    </div>
                </div>
                <div class="col-md-12 mt-n2">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12 mt-n1 mb-3" v-if="message">
                    <div class="alert alert-warning fs-10 mb-xl-0" role="alert">Start date cannot be after end date</div>
                </div>
                <div class="col-md-6 mt-n1">
                    <!-- <label>Start At: <span v-if="form.errors" v-text="form.errors.start_at" class="haveerror"></span></label> -->
                    <date-picker
                        v-model:value="semester.start"
                        type="month" format="YYYY-MM"
                        lang="en"
                        :disabled-date="disabledBeforeTodayAndAfterAWeek"
                        placeholder="Start Month"
                        >
                    </date-picker>
                </div>
                <div class="col-md-6 mt-n1">
                    <!-- <label>End At: <span v-if="form.errors" v-text="form.errors.end_at" class="haveerror"></span></label> -->
                    <date-picker
                        v-model:value="semester.end"
                        type="month" format="YYYY-MM"
                        lang="en"
                        :disabled-date="disabledBeforeTodayAndAfterAWeek"
                        placeholder="End Month"
                        >
                    </date-picker>
                </div>
                <div class="col-md-12 mt-1 mb-n2">
                    <div class="alert alert-danger fs-11 mb-xl-0" role="alert">Please note that it is very important to double-check the selected <b>month and year</b>.</div>
                </div>
                <div class="col-md-12 mt-1 mb-n3">
                    <hr class="text-muted mb-2"/>
                    <p class="fs-12 text-muted float-right mb-2">Please type the key <span class="text-dark fw-semibold fs-12">{{key}}</span> to confirm</p>
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
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    components: { Multiselect, DatePicker },
    props: ['settings'],
    data(){
        return {
            currentUrl: window.location.origin,
            form : {},
            selected: '',
            semester: {
                start: null,
                end: null,
                semester: {}
            },
            key: '',
            final: '',
            message: false,
            showModal: false,
        }
    },
   computed : {
        name : function() {
            if(this.selected.term == 'Semester'){
                this.semester.semester = this.settings.semester;
                return (this.settings.semester) ? this.settings.semester.name : 'not set';
            }else if(this.selected.term == 'Trimester'){
                this.semester.trimester = this.settings.trimester;
                return (this.settings.trimester) ? this.settings.trimester.name : 'not set';
            }else{
                this.semester.quarter = this.settings.quarter;
                return (this.settings.quarter) ? this.settings.quarter.name : 'not set';
            }
        },
        month : function() {
            if (this.semester.start !== '' && this.semester.end !== '' && this.semester.start > this.semester.end) {
                this.message = true;
                return;
            }else{
                this.message = false;
            }

            if(this.semester.start != null && this.semester.end != null){
                var start = new Date(this.semester.start.getFullYear(),this.semester.start.getMonth() + 1, 0).toLocaleDateString("af-ZA");
                var end = new Date(this.semester.end.getFullYear(),this.semester.end.getMonth() + 1, 0).toLocaleDateString("af-ZA");
                return this.formatDate(start)+' - '+this.formatDate(end);
            }else{
                return null;
            }
        }
   },
    methods : {
        show(data){
            this.key = Math.random().toString(36).slice(2);
            this.selected = data;
            this.showModal = true;
        },
        create(){
            this.form = this.$inertia.form({
                school_id: this.selected.id,
                academic_year: this.settings.academic_year,
                start_at: (this.semester.start != '') ? new Date(this.semester.start).toLocaleDateString("af-ZA") : '',
                end_at: (this.semester.end != '') ? new Date(this.semester.end.getFullYear(),this.semester.end.getMonth() + 1, 0).toLocaleDateString("af-ZA"): '',
                year: this.settings.year,
                semester_id: this.semester.semester.id,
                editable: false,
                type: 'semester'
            })

            this.form.post('/schools',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        formatDate(test){
            const [year, month] = test.split('-');
            const monthNames = [
                'January', 'February', 'March', 'April',
                'May', 'June', 'July', 'August',
                'September', 'October', 'November', 'December'
            ];
            const monthName = monthNames[parseInt(month, 10) - 1];
            return `${monthName} ${year}`;
        },
        hide(){
            this.final = '';
            this.semester.start = null;
            this.semester.end = null;
            this.showModal = false;
        }
    }
}
</script>
<style>
.form-floating > .form-control {
  height: 50px;
  min-height: 50px;
  line-height: 1;
  padding-top: 1.300rem;
  font-size: 12px;
}
</style>