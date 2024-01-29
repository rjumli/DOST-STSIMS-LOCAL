<template lang="">
    <Head title="Insights"/>
    <PageHeader :title="title" :items="items"/>
    <b-row style="height: calc(100vh - 225px); overflow: auto;">
        <b-col xxl="4">
            
        </b-col>
        <b-col xxl="8" order-xxl="0" class="order-first">
            <Count :statistics="statistics.countings" ref="count"/>
        </b-col>
    </b-row>
</template>
<script>
import Count from './Components/Count.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props: ['statistics'],
    components : { PageHeader, Count },
    data(){
        return {
            currentUrl: window.location.origin,
            title: "Insights",
            items: [{text: "Insights",href: "/",}, {text: "Scholar",active: true, }, ],
        }
    }
}
</script>