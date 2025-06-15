<template>
    <div class="container">
        <div v-if="props.groups !== undefined" class="group" v-for="(v1, k1) in props.groups" v-bind:key="k1">
            <p class="gname">{{ v1.group.name }}</p>
            <ul v-for="(v2, k2) in v1.boards" v-bind:key="k2">
                <li><router-link v-bind:to="{ name: 'threads', params: {bbsid: v2.id} }">{{ v2.name }}</router-link></li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
div.wrap {
    margin: 0 auto;
}
div.container {
    width: 80%;
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: space-around;
    margin: 0 auto;
}
div.group {
    width: 45%;
    margin-bottom: 20px;
}
p.gname {
    font-size: 24px;
}

ul > li {
    text-decoration: underline;
}
</style>

<script setup lang="ts">
const props = defineProps({
    groups: Object,
});
console.log(props.groups)
</script>
