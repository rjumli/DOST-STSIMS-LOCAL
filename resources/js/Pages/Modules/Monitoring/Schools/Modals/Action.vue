<template>
    <b-modal v-model="showModal" title="Confirm" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row g-2">
                <div class="col-md-12">
                    <Multiselect class="form-control"
                    placeholder="Select Action"
                    v-model="type" :close-on-select="true" 
                    :searchable="false" 
                    :options="['Create Semester','Add Qualifier','Endorse Qualifier']"/>
                </div>
            </div>
            <div class="row" v-if="type == 'Create Semester'">
                <div class="col-md-12 mb-2">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <input type="text" v-model="settings.academic_year" class="form-control" readonly>
                        <label>Academic Year</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Start At: <span v-if="form.errors" v-text="form.errors.start_at" class="haveerror"></span></label>
                    <date-picker
                        v-model:value="semester.start"
                        type="month" format="YYYY-MM"
                        lang="en"
                        :disabled-date="disabledBeforeTodayAndAfterAWeek"
                        placeholder="Select Month"
                        >
                    </date-picker>
                </div>
                <div class="col-md-6">
                    <label>End At: <span v-if="form.errors" v-text="form.errors.end_at" class="haveerror"></span></label>
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
            {{settings}}
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
    props: ['dropdowns'],
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
                from: '',
                to: '',
                start: '',
                end: '',
                year: '',
                semester: {}
            },
            form : {},
            code: '',
            confirmation: '',
            showModal: false,
            selected: [],
            schools: [],
            settings: {},
            type: ''
        }
    },
    methods : {
        show(data,schools,settings){
            this.selected = data;
            this.schools = schools;
            this.settings = settings.data;
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
        hide(){
            this.code = '';
            this.confirmation = '';
            this.showModal = false;
        }
    }
}
</script>