<template>
    <b-modal v-model="showModal" title="New Academic Year" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-n3">
            <div class="row customerform mt-2">
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
                <div class="col-md-6" style="margin-top: 11px;">
                   <div class="form-group">
                        <label>Academic Year:</label>
                        <input type="text" class="form-control" v-model="academic_year" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <hr class="text-muted"/>
                </div>
                  <!-- <div class="col-md-12">
                    <hr class="text-muted"/>
                </div> -->
               <p class="text-center text-muted">Please type the key <span class="text-dark fw-semibold">{{key}}</span> to confirm</p>
                 <div class="col-md-12" style="margin-top: 11px;">
                   <div class="form-group">
                        <!-- <label>Academic Year:</label> -->
                        <input type="text" class="form-control text-center" v-model="final">
                    </div>
                </div>
                
                <div class="col-md-12 mt-2">
                    <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow" role="alert">
                        <i class="ri-alert-line label-icon"></i>Mark all active semesters into inactive.
                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="final != key" block>Save</b-button>
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
            showModal: false,
            year: '',
            y: '',
            final: '',
            form: {},
            key: '',
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
            this.showModal = true;
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
