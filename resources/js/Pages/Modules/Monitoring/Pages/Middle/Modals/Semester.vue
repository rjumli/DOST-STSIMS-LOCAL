<template>
    <b-modal v-model="showModal" title="Update Semester" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row customerform">
                <div class="col-md-12">
                    <label>Semester: <span v-if="form.errors" v-text="form.errors.semester_id" class="haveerror"></span></label>
                    <multiselect v-model="semester" id="ajax" label="name" track-by="name"
                        placeholder="Select Semester" open-direction="bottom" :options="semesters"
                        :allow-empty="false"
                        :show-labels="false">
                    </multiselect> 
                </div>
                <!-- <div class="col-md-6 mt-2">
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
                <div class="col-md-6 mt-2">
                    <label>End At: <span v-if="form.errors" v-text="form.errors.end_at" class="haveerror"></span></label>
                    <date-picker
                        v-model:value="semester.end"
                        type="month" format="YYYY-MM"
                        lang="en"
                        :disabled-date="disabledBeforeTodayAndAfterAWeek"
                        placeholder="Select Month"
                        >
                    </date-picker>
                </div> -->
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
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
            showModal: false,
            type: '',
            semester: '',
            form: {},
        }
    },

    computed : {
        academic_year : function(){
            if(this.semester.from != ''){
                this.year = new Date(this.semester.from).getFullYear();
                return new Date(this.semester.from).getFullYear()+'-'+ (Number(new Date(this.semester.from).getFullYear())+1);
            }else{
                return '';
            }
        },
        semesters : function() {
            return this.dropdowns.data.filter(x => x.classification === this.type);
        },
    },
    methods : {
        show(type) {
            this.type = type;
            this.showModal = true;
        },
        create(){
            this.form = this.$inertia.form({
                semester: this.semester,
                type: this.type
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
            this.type = '';
        }
    }
}
</script>
