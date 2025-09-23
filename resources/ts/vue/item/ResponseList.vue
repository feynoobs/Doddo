<template>
    <div class="inner_wrap" v-if="data !== undefined">
        <div class="tname">{{ data!.thread.name }}</div>
        <dl class="response">
            <template v-for="(v, k) in data!.responses">
                <dt>
                    <span class="id">{{ k+1 }}</span>
                    <span class="name">名前:</span>
                    <template v-if="v.email === null">
                        <span class="rname">{{ v.name }}</span>
                    </template>
                    <template v-else>
                        <a v-bind:href="`mailto:${v.email}`">
                            <span class="rname">{{ v.name }}</span>
                        </a>
                    </template>
                    <time v-bind:datetime="v.created_at" v-text="dateformat(v.created_at)"></time>
                    <span class="uid">
                        <span v-if="v.uid !== null">ID:{{ v.uid }}</span>
                    </span>
                </dt>
                <dd>
                    <div class="message">
                        {{ v.message }}
                    </div>
                </dd>
            </template>
        </dl>
    </div>
</template>

<style scoped>

dl.response {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
}

dl.response > dt {
    width: 100%;
    color: #333;
}
dl.response > dd {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    width: 100%;
    color: #333;
    margin-bottom: 10px;
}

dl.response > dd > div.message {
    width: 90%;
    margin: 5px 0;
}

dl.response > dt > span.id {
    display: inline-block;
    width: 35px;
    font-weight: normal;
}

dl.response > dt > span.name {
    display: inline-block;
    width: 50px;
    font-weight: normal;
}

dl.response > dt > span.rname {
    display: inline-block;
    width: auto;
    min-width: 150px;
    font-weight: normal;
}

dl.response > dt > a > span.rname {
    display: inline-block;
    width: auto;
    margin-right: 10px;
    color: blue;
}

dl.response > dt > time {
    display: inline-block;
    width: 30%;
    font-weight: normal;
}

dl.response > dd {
    width: 100%;
}

div.inner_wrap > div.tname{
    margin-bottom: 20px;
    font-size: 28px;
    color: red;
}
</style>

<script setup lang="ts">
defineProps({
    data: Object,
});

const dateformat = (date: string) : string =>
{
    const d = new Date(date)
    const year = d.getFullYear()
    const month = String(d.getMonth() + 1).padStart(2, '0')
    const day = String(d.getDate()).padStart(2, '0')
    const week = ['日', '月', '火', '水', '木', '金', '土'][d.getDay()]
    const hour = String(d.getHours()).padStart(2, '0')
    const minute = String(d.getMinutes()).padStart(2, '0')
    const second = String(d.getSeconds()).padStart(2, '0')

    return `${year}/${month}/${day}(${week}) ${hour}:${minute}:${second}`
}

// console.log(props.data)
</script>