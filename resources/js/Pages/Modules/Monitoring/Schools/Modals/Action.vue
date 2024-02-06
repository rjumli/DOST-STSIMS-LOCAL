<template>
    <b-modal v-model="showModal" title="Confirm" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row g-2">
                <div class="col-md-12">
                    <Multiselect class="form-control"
                    placeholder="Select Action"
                    v-model="type" :close-on-select="true" 
                    :searchable="false" 
                    :options="['Create Semester','Close Enrollment']"/>
                </div>
            </div>
            <div class="row" v-if="type == 'Create Semester'">
                <div class="col-md-12 mb-2">
                    <hr class="text-muted"/>
                </div>
                <div class="mt-n2" :class="(month)?'col-md-6':'col-md-12'">
                    <div class="form-floating">
                        <input type="text" :value="settings.academic_year+' - '+name" class="form-control" readonly>
                        <label>Academic Year</label>
                    </div>
                </div>
                 <div class="col-md-6 mt-n2" v-if="month">
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
                        placeholder="Select Month"
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
                        placeholder="Select Month"
                        >
                    </date-picker>
                </div>
            </div>
            {{selected}}
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button v-if="code == confirmation" @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
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
    props: ['dropdowns','settings'],
    data(){
        return {
            currentUrl: window.location.origin,
            errors: [],
            user: {
                status: {},
                address: {
                    info: {}
                },
                profile: {},
            },
             semester: {
                start: null,
                end: null,
                semester: {}
            },
            form : {},
            code: '',
            confirmation: '',
            showModal: false,
            selected: [],
            schools: [],
            type: ''
        }
    },
    computed: {
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
        show(data,schools){
            this.selected = data;
            this.schools = schools;
            this.showModal = true;
        },
        create(){
            this.form.post('/scholars/qualifiers',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('status',true);
                    this.hide();
                },
                onError: () => {
                    this.hide();
                }
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
            this.code = '';
            this.confirmation = '';
            this.showModal = false;
        }
    }
}
</script>