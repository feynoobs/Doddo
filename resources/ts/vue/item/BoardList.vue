<template>
    <div class="inner_wrap">
        <div v-if="props.data !== undefined" class="gname">{{ props.data!.group.name }}</div>
        <ul class="group">
            <li v-for="(v, k) in props.data!.boards" v-bind:key="k" class="link"><router-link v-bind:to="{name: 'board', params:{id: v.id}}">{{ v.name }}</router-link></li>
        </ul>
    </div>
</template>

<style scoped>
div.gname {
    font-size: 24px;
    color: #333;
    margin: 20px 0 10px 0;
}
li.link {
    line-height: 1.3;
    display: inline;
    text-decoration: underline;
    padding-right: 5px;
    color: #333;
}
li.link:hover {
    color: blue;
}
div.inner_wrap {
    margin: 20px 1%;
    width: calc((100% - 2% * 6) / 3);
}
</style>

<script setup lang="ts">
const props = defineProps({
    data: Object
});
</script>