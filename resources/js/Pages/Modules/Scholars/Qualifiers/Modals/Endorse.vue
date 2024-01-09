<template>
    <b-modal v-model="showModal" title="Endorse Qualifier"  style="--vz-modal-width: 600px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    
        <ul class="list-unstyled mb-0 vstack gap-3" v-if="user">
            <li>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img :src="currentUrl+'/imagess/avatars/'+user.profile.avatar" alt="" class="avatar-sm rounded">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-14 mb-1 text-primary">{{user.profile.name}}</h6>
                        <span :class="'badge bg-secondary '+user.status.color+' '+user.status.others">{{user.status.name}}</span>
                    </div>
                </div>
            </li>
            <hr class="text-muted mt-0" />
        </ul>

        <b-form class="customform mb-2">
            <div class="row customerform">
               <div class="col-md-12">
                    <label>School: <span v-if="errors.length > 0" class="haveerror">({{ errors[0].school_id }})</span></label>
                    <Multiselect class="form-control" @search-change="fetchSchool"
                    placeholder="Select School" label="name" trackBy="name"
                    v-model="school" :close-on-select="true" 
                    :searchable="true" :options="schools"/>
                </div>
                <div class="col-md-12 mt-2" v-if="school">
                    <label>Course: <span v-if="errors.length > 0" class="haveerror">({{ errors[0].course_id }})</span></label>
                    <Multiselect class="form-control"
                    placeholder="Select Course" label="course" trackBy="course"
                    v-model="course" :close-on-select="true" 
                    :searchable="false" :options="courses"/>
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
    components: { Multiselect },
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
            form : {},
            profile_id : '',
            lrn: '',
            program_id: '',
            schools: [],
            courses: [],
            school: '',
            course: '',
            showModal: false,
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
            this.errors = [];
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        },
        create(){
             this.form = this.$inertia.form({
                user: this.user,
                school_id: this.school,
                course_id: this.course,
                type: 'endorse'
            })
            this.form.post('/scholars/qualifiers',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.clear();
                },
            });
        },
        clear(){
            this.$emit('status',true);
            this.school = '';
            this.course = '';
            this.showModal = false;
        },
        fetchSchool(value) {
            if(value.length > 5){
                axios.post(this.currentUrl + '/lists/search/schools', {
                    word: value,
                })
                .then(response => {
                    this.schools = response.data.data;
                })
                .catch(err => console.log(err));
            }
        },
        fetchCourses(id){
            axios.get(this.currentUrl + '/lists/search/courses/'+id)
            .then(response => {
                this.courses = response.data.data;
            })
            .catch(err => console.log(err));
        }
    }
}
</script>