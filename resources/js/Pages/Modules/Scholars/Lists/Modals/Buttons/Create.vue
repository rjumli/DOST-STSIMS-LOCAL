<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" size="xl" class="v-modal-custom" modal-class="zoomIn" centered>
        <template v-slot:header>
            <h5 class="modal-title">Create Scholar</h5>
            <button @click="hide()" type="button" class="btn-close" aria-label="Close"></button>
        </template>
        <div class="row" style="height: calc(100vh - 240px); overflow: auto;">
            <b-form class="customform mb-2">
                <div class="row g-3">
                    <div class="col-md-12 mb-n4 mt-5">
                        <p class="text-muted mb-n3">Scholar Name</p>
                        <hr class="text-muted"/>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.lastname) ? 'text-danger' : '' : ''">
                            <label>Lastname</label>
                            <input type="text" v-model="scholar.profile.lastname" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.firstname) ? 'text-danger' : '' : ''">
                            <label>Firstname</label>
                            <input type="text" v-model="scholar.profile.firstname" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form" :class="(form.errors) ? (form.errors.middlename) ? 'text-danger' : '' : ''">
                            <label>Middlename</label>
                            <input type="text" v-model="scholar.profile.middlename" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form" :class="(form.errors) ? (form.errors.suffix) ? 'text-danger' : '' : ''">
                            <label>Suffix</label>
                            <input type="text" v-model="scholar.profile.suffix" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12 mb-n4 mt-4">
                        <p class="text-muted mb-n3">Scholar Infomation</p>
                        <hr class="text-muted"/>
                    </div>
                    <div class="col-md-3">
                        <div class="form" :class="(form.errors) ? (form.errors.program_id) ? 'text-danger' : '' : ''">
                            <label>Program</label>
                            <Multiselect class="form-control"
                            placeholder="Select Program" label="name" trackBy="name"
                            v-model="scholar.program" :close-on-select="true" 
                            :searchable="false" :options="program_list" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form" :class="(form.errors) ? (form.errors.subprogram_id) ? 'text-danger' : '' : ''">
                            <label>Subprogram</label>
                            <Multiselect class="form-control"
                            placeholder="Select Subprogram" label="name" trackBy="name"
                            v-model="scholar.subprogram" :close-on-select="true" 
                            :searchable="false" :options="subprogram_list" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form" :class="(form.errors) ? (form.errors.category_id) ? 'text-danger' : '' : ''">
                            <label>Category</label>
                             <Multiselect class="form-control"
                            placeholder="Select Category" label="name" trackBy="name"
                            v-model="scholar.category" :close-on-select="true" 
                            :searchable="false" :options="categories" />
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form" :class="(form.errors) ? (form.errors.status_id) ? 'text-danger' : '' : ''">
                            <label>Status</label>
                             <Multiselect class="form-control"
                            placeholder="Select Status" label="name" trackBy="name"
                            v-model="scholar.status" :close-on-select="true" 
                            :searchable="false" :options="status_list" />
                        </div>
                    </div>
                    <div class="col-md-12 mb-n4 mt-4">
                        <p class="text-muted mb-n3">Scholar Profile Infomation</p>
                        <hr class="text-muted"/>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.awarded_year) ? 'text-danger' : '' : ''">
                            <label>Awarded Year</label>
                            <input type="text" v-model="scholar.awarded_year" v-maska data-maska="####" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.account_no) ? 'text-danger' : '' : ''">
                            <label>Account no.</label>
                            <input type="text" v-model="scholar.profile.account_no" v-maska data-maska="####-####-##" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.email) ? 'text-danger' : '' : ''">
                            <label>Email</label>
                            <input type="email" v-model="scholar.profile.email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4 mt-0">
                        <div class="form" :class="(form.errors) ? (form.errors.contact_no) ? 'text-danger' : '' : ''">
                            <label>Contact No.</label>
                            <input type="text" v-model="scholar.profile.contact_no" v-maska data-maska="###########" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4 mt-0">
                        <div class="form" :class="(form.errors) ? (form.errors.sex) ? 'text-danger' : '' : ''">
                            <label>Sex</label>
                            <Multiselect class="form-control"
                            placeholder="Select Sex"
                            v-model="scholar.profile.sex" :close-on-select="true" 
                            :searchable="false" :options="['Male','Female']" />
                        </div>
                    </div>
                    <div class="col-md-4 mt-0">
                        <div class="form" :class="(form.errors) ? (form.errors.birthday) ? 'text-danger' : '' : ''">
                            <label>Birthday</label>
                            <input type="date" v-model="scholar.profile.birthday" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12 mb-n4 mt-4">
                        <p class="text-muted mb-n3">Scholar Address Infomation</p>
                        <hr class="text-muted"/>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.region_code) ? 'text-danger' : '' : ''">
                            <label>Region</label>
                            <Multiselect class="form-control"
                            placeholder="Select Region" label="name" trackBy="name"
                            v-model="scholar.address.permanent.region" :close-on-select="true" 
                            :searchable="true" :options="regions" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.province_code) ? 'text-danger' : '' : ''">
                            <label>Province</label>
                            <Multiselect class="form-control"
                            placeholder="Select Province" label="name" trackBy="name"
                            v-model="scholar.address.permanent.province" :close-on-select="true" 
                            :searchable="true" :options="provinces" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.municipality_code) ? 'text-danger' : '' : ''">
                            <label>Municipality</label>
                            <Multiselect class="form-control"
                            placeholder="Select Municipality" label="name" trackBy="name"
                            v-model="scholar.address.permanent.municipality" :close-on-select="true" 
                            :searchable="true" :options="municipalities" />
                        </div>
                    </div>
                    <div class="col-md-4 mt-0">
                        <div class="form" :class="(form.errors) ? (form.errors.barangay_code) ? 'text-danger' : '' : ''">
                            <label>Barangay</label>
                             <Multiselect class="form-control"
                            placeholder="Select Barangay" label="name" trackBy="name"
                            v-model="scholar.address.permanent.barangay" :close-on-select="true" 
                            :searchable="true" :options="barangays" />
                        </div>
                    </div>
                    <div class="col-md-8 mt-0">
                        <div class="form" :class="(form.errors) ? (form.errors.address) ? 'text-danger' : '' : ''">
                            <label>Address</label>
                            <input type="text"  v-model="scholar.address.permanent.address" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12 mb-n4 mt-3">
                        <p class="text-muted mb-n3">Scholar Education Infomation</p>
                        <hr class="text-muted"/>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.school_id) ? 'text-danger' : '' : ''">
                            <label>School</label>
                            <Multiselect class="form-control" @search-change="fetchSchool"
                            placeholder="Select School" label="name" trackBy="name"
                            v-model="scholar.education.school" :close-on-select="true" 
                            :searchable="true" :options="schools"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.course_id) ? 'text-danger' : '' : ''">
                            <label>Course</label>
                            <Multiselect class="form-control"
                            placeholder="Select Course" label="course" trackBy="course"
                            v-model="scholar.education.course" :close-on-select="true" 
                            :searchable="false" :options="courses"/>
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="form" :class="(form.errors) ? (form.errors.level_id) ? 'text-danger' : '' : ''">
                            <label>Level</label>
                            <Multiselect class="form-control"
                            placeholder="Select Course" label="name" trackBy="name"
                            v-model="scholar.education.level" :close-on-select="true" 
                            :searchable="false" :options="levels"/>
                        </div>
                    </div>
                </div>
            </b-form>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Proceed</b-button>
        </template>
    </b-modal>
</template>
<script>
import { vMaska } from "maska"
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    props: ['dropdowns','regions','program_list','subprogram_list','statuses'],
    components : { Multiselect },
    directives: { maska: vMaska },
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            scholar: {
                program: '',
                subprogram: '',
                category: '',
                status: '',
                awarded_year: '',
                started_year: '',
                profile: {
                    account_no: '',
                    email: '',
                    firstname: '',
                    middlename: '',
                    lastname: '',
                    suffix: '',
                    sex: '',
                    contact_no: '',
                    birthday: ''
                },
                education: {
                    school: '',
                    course: '',
                    level: ''
                },
                address: {
                    permanent: {
                        address: '',
                        region: '',
                        province: '',
                        municipality: '',
                        barangay: ''
                    },
                    current: {
                        address: '',
                        region: '',
                        province: '',
                        municipality: '',
                        barangay: ''
                    }
                }
            },
            form: {},
            provinces: [],
            municipalities: [],
            barangays: [],
            schools: [],
            courses: [],
        }
    },
    watch: {
        'scholar.address.permanent.region'(){
            this.scholar.address.permanent.province = '';
            this.scholar.address.permanent.municipality = '';
            this.scholar.address.permanent.barangay = '';
            this.fetchProvince(this.scholar.address.permanent.region);
        },
        'scholar.address.permanent.province'(){
            this.scholar.address.permanent.barangay = '';
            this.scholar.address.permanent.municipality = '';
            this.fetchMunicipality(this.scholar.address.permanent.province);
        },
        'scholar.address.permanent.municipality'(){
            this.scholar.address.permanent.barangay = '';
            this.fetchBarangay(this.scholar.address.permanent.municipality);
        },
        'scholar.education.school'(){
            this.fetchCourses(this.scholar.education.school);
        }
    },
    computed: {
        categories : function() {
            return this.dropdowns.data.filter(x => x.classification == 'Category');
        },
        levels : function() {
            return this.dropdowns.data.filter(x => x.classification == 'Level');
        },
        status_list : function() {
            return this.statuses.filter(x => x.type == 'Progress' || x.type == 'Ongoing');
        }
    },
    methods : {
        show(){
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
                program_id: (this.scholar.program) ? this.scholar.program : '',
                subprogram_id: (this.scholar.subprogram) ? this.scholar.subprogram : '',
                category_id: (this.scholar.category) ? this.scholar.category : '',
                status_id: (this.scholar.status) ? this.scholar.status : '',
                awarded_year: this.scholar.awarded_year,
                started_year: this.scholar.awarded_year,
                account_no: this.scholar.profile.account_no,
                email: this.scholar.profile.email,
                firstname: this.scholar.profile.firstname,
                middlename: this.scholar.profile.middlename,
                lastname: this.scholar.profile.lastname,
                suffix: this.scholar.profile.suffix,
                sex: this.scholar.profile.sex,
                contact_no: this.scholar.profile.contact_no,
                birthday: this.scholar.profile.birthday,
                school_id: (this.scholar.education.school) ? this.scholar.education.school : '',
                course_id: (this.scholar.education.course) ? this.scholar.education.course : '',
                level_id: (this.scholar.education.level) ? this.scholar.education.level : '',
                address: this.scholar.address.permanent.address,
                region_code: (this.scholar.address.permanent.region) ? this.scholar.address.permanent.region : '',
                province_code: (this.scholar.address.permanent.province) ? this.scholar.address.permanent.province : '',
                municipality_code: (this.scholar.address.permanent.municipality) ? this.scholar.address.permanent.municipality : '',
                barangay_code: (this.scholar.address.permanent.barangay) ? this.scholar.address.permanent.barangay : '',
                type: 'create'
            })

            this.form.post('/scholars/listing',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                }
            });
        },
        hide(){
            this.showModal = false;
        },
        fetchProvince($id){
            axios.get(this.currentUrl + '/lists/provinces/'+$id)
            .then(response => {
                this.provinces = response.data.data;
            })
            .catch(err => console.log(err));
        },
        fetchMunicipality($id){
            axios.get(this.currentUrl + '/lists/municipalities/'+$id)
            .then(response => {
                this.municipalities = response.data.data;
            })
            .catch(err => console.log(err));
        },
        fetchBarangay($id){
            axios.get(this.currentUrl + '/lists/barangays/'+$id)
            .then(response => {
                this.barangays = response.data.data;
            })
            .catch(err => console.log(err));
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
