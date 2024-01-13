<template>
    <Head :title="school.data.name" />
    <Top :school="school"/>
     <b-row class="g-3">
       <Sidebar :id="school.data.id"/>
        <b-col lg="8">
            <b-row>
                <Count :school="school"/>
                <b-col lg="12 mt-n2">
                    <b-card no-body>
                        <b-card-body style="height: calc(100vh - 385px)">
                            <BTabs nav-class="nav-pills nav-custom nav-custom-light" pills small>
                                <BTab title="Overview">
                                    <Overview/>
                                </BTab>
                                <BTab title="Courses">
                                    <Course :id="school.data.id" :term="school.data.term"/>
                                </BTab>
                                <BTab title="Semesters"></BTab>
                                <BTab title="Grading"></BTab>
                            </BTabs>
                        </b-card-body>
                    </b-card>
                </b-col>
            </b-row>
       </b-col>
    </b-row>
</template>
<script>
import Top from './Top.vue';
import Sidebar from './Sidebar.vue';
import Count from './Count.vue';
import Course from './Pages/Course.vue';
// import Grading from './Pages/Grading.vue';
import Overview from './Pages/Overview.vue';
// import Semester from './Pages/Semester.vue';
export default {
    props: ['school','dropdowns'],
    components: { Top, Sidebar, Count, Overview, Course }
}
</script>