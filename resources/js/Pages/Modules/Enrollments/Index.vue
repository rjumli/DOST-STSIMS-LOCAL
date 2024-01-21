<template>
    <Head title="Enrollments"/>
    <PageHeader :title="title" :items="items" />
     <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <Sidebar />
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <Prospectus v-if="show == 'prospectus'" ref="prospectus"/>
            <Enrollment v-else-if="show == 'enrollment'"  ref="enrollment"/>
            <Assessment v-else-if="show == 'assessment'" ref="assessment"/>
        </div>
    </div>
</template>
<script>
import Assessment from './Pages/Assessment.vue';
import Enrollment from './Pages/Enrollment.vue';
import Prospectus from './Pages/Prospectus.vue';
import Sidebar from './Sidebar.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader, Sidebar, Prospectus, Assessment, Enrollment },
    data(){
        return {
            currentUrl: window.location.origin,
            title: "List of Enrollments",
            items: [{text: "Enrollments",href: "/"},{text: "List",active: true}],
            show: 'default',
            lists: null,
            selected: null,
            enrollment: null
        }
    },
    methods: {
        showProspectus(data){
            this.show = 'prospectus'; 
            this.$nextTick(function(){ this.$refs.prospectus.set(data) });
        },
        showEnrollment(info,data){
            this.show = 'enrollment';
            this.$nextTick(function(){ this.$refs.enrollment.set(info,data) });
        },
        showAssessment(data,gradings){
            this.show = 'assessment';
            this.$nextTick(function(){ this.$refs.assessment.set(data,gradings) });
        }
    }
}
</script>