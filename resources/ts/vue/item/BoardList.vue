<template>
    <details class="inner_wrap">
        <summary v-if="data !== undefined" class="gname">{{ data!.group.name }}</summary>
        <ul>
            <li v-for="(v, k) in data!.boards" v-bind:key="k" class="link"><router-link v-bind:to="{name: 'board', params:{id: v.id}}">{{ v.name }}</router-link></li>
        </ul>
    </details>
</template>

<style scoped>
summary.gname {
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
details.inner_wrap {
    margin: 20px 30px;
    width: calc((100% - 2% * 6) / 3);
}

details[open] > ul {
    animation: fadein 0.3s ease;
}
@keyframes fadein {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script setup lang="ts">
defineProps({
    data: Object
});
</script>