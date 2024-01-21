<template>
    <hr class="text-muted"/>
    <b-form class="customform mb-n1 mt-0 g-2">
        <table class="table align-middle mb-0">
            <thead class="table-light"> <tr><th scope="col">I. Personal Information</th></tr></thead>
        </table>
        <div class="row g-1 mt-2">
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
        </div>
        <div class="row g-1">
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" v-model="user.profile.email" class="form-control" readonly>
                    <label>Email</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" v-model="user.profile.contact_no" class="form-control" readonly>
                    <label>Contact No.</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" v-model="user.profile.birthday" class="form-control" readonly>
                    <label>Birthday</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" v-model="user.profile.sex" class="form-control" readonly>
                    <label>Sex</label>
                </div>
            </div>
        </div>
        <div class="row g-1">
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" value="-" class="form-control" readonly>
                    <label>Father</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" value="-" class="form-control" readonly>
                    <label>Mother</label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="text" :value="user.addresses[0].address+', '+user.addresses[0].name" class="form-control" readonly>
                    <label>Address</label>
                </div>
            </div>
        </div>
        <table class="table align-middle mb-0 mt-2">
            <thead class="table-light"> <tr><th scope="col">II. Scholarship Information</th></tr></thead>
        </table>
        <div class="row g-1 mt-2">
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" :value="user.program" class="form-control" readonly>
                    <label>Program</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" :value="user.awarded_year" class="form-control" readonly>
                    <label>Year Awarded</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" :value="user.education.school.name" class="form-control" readonly>
                    <label>School</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" :value="user.education.course.name" class="form-control" readonly>
                    <label>Course</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" :value="user.education.graduated_year" class="form-control" readonly>
                    <label>Year Graduated</label>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="form-floating">
                    <input type="text" :value="user.education.award" class="form-control" readonly>
                    <label>Award</label>
                </div>
            </div>
        </div>
        <table class="table align-middle mb-0 mt-2">
            <thead class="table-light"> <tr><th scope="col">III. Employment Information</th></tr></thead>
        </table>
        <div class="row g-1 mt-2">
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" value="-" class="form-control" readonly>
                    <label>Employment Status</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" value="-" class="form-control" readonly>
                    <label>Company</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" value="-" class="form-control" readonly>
                    <label>Address</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="text" value="-" class="form-control" readonly>
                    <label>Sector</label>
                </div>
            </div>
            <div class="col-lg-12">
                <table class="table table-bordered table-nowrap">
                    <thead>
                        <tr class="fs-10">
                            <th width="25%" class="text-center">Position</th>
                            <th width="15%" class="text-center">Inclusive Period of Employment</th>
                            <th width="20%" class="text-center">Is Job Related To Undergraduate Academic Training?</th>
                            <th width="20%" class="text-center">Company</th>
                            <th width="20%" class="text-center">Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="employements.length > 0" v-for="(list,index) in employements" v-bind:key="index">
                            <th class="text-center">{{ list.position }}</th>
                            <td class="text-center">{{ list.period }}</td>
                            <td class="text-center">{{ list.is_related }}</td>
                            <td class="text-center">{{ list.company }}</td>
                            <td class="text-center">{{ list.address }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center text-muted fs-10">No Employment History</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table class="table align-middle mb-0 mt-n1">
            <thead class="table-light"> <tr><th scope="col">IV. Professional Affiliation</th></tr></thead>
        </table>
        <div class="row g-1 mt-2">
            <div class="col-lg-12">
                <table class="table table-bordered table-nowrap">
                    <thead>
                        <tr class="fs-10">
                            <th width="25%" class="text-center">Orgranization</th>
                            <th width="25%" class="text-center">Address</th>
                            <th width="25%" class="text-center">Position</th>
                            <th width="25%" class="text-center">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="affiliations.length > 0" v-for="(list,index) in affiliations" v-bind:key="index">
                            <th class="text-center">{{ list.organization }}</th>
                            <td class="text-center">{{ list.address }}</td>
                            <td class="text-center">{{ list.position }}</td>
                            <td class="text-center">{{ list.duration}}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center text-muted fs-10">No Current Professional Affiliation</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table class="table align-middle mb-0 mt-n1">
            <thead class="table-light"> <tr><th scope="col">V. Research and Development Involvement</th></tr></thead>
        </table>
        <div class="row g-1 mt-2">
            <div class="col-lg-12">
                <table class="table table-bordered table-nowrap">
                    <thead>
                        <tr class="fs-10">
                            <th width="25%" class="text-center">Field/Title Reasearch</th>
                            <th width="25%" class="text-center">Location/Duration</th>
                            <th width="25%" class="text-center">Fund Source</th>
                            <th width="25%" class="text-center">Nature of Involvement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="researches.length > 0" v-for="(list,index) in researches" v-bind:key="index">
                            <th class="text-center">{{ list.title }}</th>
                            <td class="text-center">{{ list.duration }}</td>
                            <td class="text-center">{{ list.source }}</td>
                            <td class="text-center">{{ list.involvement}}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center text-muted fs-10">No Research & Development Involvement</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table class="table align-middle mb-0 mt-n1">
            <thead class="table-light"> <tr><th scope="col">VI. Professional Recognitions</th></tr></thead>
        </table>
        <div class="row g-1 mt-2">
            <div class="col-lg-12">
                <table class="table table-bordered table-nowrap">
                    <thead>
                        <tr class="fs-10">
                            <th width="25%" class="text-center">Title of Award</th>
                            <th width="25%" class="text-center">Awarding Body</th>
                            <th width="25%" class="text-center">Date Given</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="awards.length > 0" v-for="(list,index) in awards" v-bind:key="index">
                            <th class="text-center">{{ list.title }}</th>
                            <td class="text-center">{{ list.awarding }}</td>
                            <td class="text-center">{{ list.date }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center text-muted fs-10">No Professional Awards/Recognition Received</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </b-form>
</template>
<script>
export default {
    props: ['user'],
    data(){
        return {
            currentUrl: window.location.origin,
            employements: [],
            affiliations: [],
            researches: [],
            awards: []
        }
    },
}
</script>
<style>
.form-floating > .form-control {
  height: 45px;
  min-height: 45px;
  line-height: 1;
  padding-top: 1.300rem;
  font-size: 12px;
}
</style>
<!-- 
<BTabs navClass="custom-verti-nav-pills text-center" navWrapperClass="col-md-3" contentClass="text-muted mt-3 mt-lg-0 col-md-9" pills vertical justified>
    <BTab active class="row">
        <template #title>
            Personal Information
        </template>
    </BTab>
    <BTab>
        <template #title>
            Scholarship Information
        </template>
    </BTab>
    <BTab>
        <template #title>
            Employment Information
        </template>
    </BTab>
    <BTab>
        <template #title>
            Professional Affiliation
        </template>
    </BTab>
    <BTab>
        <template #title>
            R&D Involvement
        </template>
    </BTab>
    <BTab>
        <template #title>
            Professional Awards/Recognition
        </template>
    </BTab>
</BTabs> -->