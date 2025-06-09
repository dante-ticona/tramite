<template>
    <div id="app">
        <button type="button"
            @click="$refs.chart.add({id: +new Date(), x: 10, y: 10, name: 'New', type: 'operation', approvers: []})">
            Add
        </button>
        <button type="button" @click="$refs.chart.remove()">
            Del
        </button>
        <button type="button" @click="$refs.chart.editCurrent()">
            Edit
        </button>
        <button type="button" @click="$refs.chart.save()">
            Save
        </button>
        <flowchart :nodes="nodes" :connections="connections" @editnode="handleEditNode" @dblclick="handleDblClick"
            @editconnection="handleEditConnection" @save="handleChartSave" ref="chart">
        </flowchart>
    </div>
</template>
<script>
    import Vue from 'vue';
    import FlowChart from 'flowchart-vue';

    Vue.use(FlowChart);

    export default {
        name: 'App',
        data: function () {
            return {
                nodes: [
                    // Basic fields
                    { id: 1, x: 20, y: 20, name: 'Inicio', type: 'start', description: 'xxx', width: 50, height: 50, color: 'green' },
                    // You can add any generic fields to node, for example: description
                    // It will be passed to @save, @editnode
                    { id: 2, x: 100, y: 100, name: '20', type: 'operation', description: 'Im here', approvers: [{ id: 1, name: '3333333 444' },] },
                    { id: 3, x: 180, y: 180, name: '30', type: 'operation', description: 'Im here', approvers: [{ id: 1, name: '5555555555 666666' },] },
                    { id: 5, x: 260, y: 260, name: '40', type: 'operation', description: 'Im here', approvers: [{ id: 1, name: '7777 88888888' },] },
                    { id: 4, x: 360, y: 360, name: 'Fin', type: 'end', description: 'yyy2', width: 50, height: 50 },
                    { id: 6, x: 420, y: 420, name: 'Fin', type: 'end', description: 'yyy2', width: 50, height: 50 },
                ],
                connections: [
                    {
                        source: { id: 1, position: 'bottom' },
                        destination: { id: 2, position: 'left' },
                        id: 1,
                        type: 'pass',
                    }, {
                        source: { id: 2, position: 'top' },
                        destination: { id: 1, position: 'top' },
                        id: 2,
                        type: 'reject',
                    }, {
                        source: { id: 2, position: 'bottom' },
                        destination: { id: 3, position: 'left' },
                        id: 3,
                        type: 'pass',
                    }, {
                        source: { id: 2, position: 'bottom' },
                        destination: { id: 5, position: 'left' },
                        id: 5,
                        type: 'pass',
                    }, {
                        source: { id: 3, position: 'right' },
                        destination: { id: 6, position: 'left' },
                        id: 4,
                        type: 'reject',
                    }, {
                        source: { id: 5, position: 'right' },
                        destination: { id: 4, position: 'left' },
                        id: 4,
                        type: 'pass',
                    }
                ],
            };
        },
        methods: {
            handleChartSave(nodes, connections) {
                // axios.post(url, {nodes, connections}).then(resp => {
                //   this.nodes = resp.data.nodes;
                //   this.connections = resp.data.connections;
                //   // Flowchart will refresh after this.nodes and this.connections changed
                // });
            },
            handleEditNode(node) {
                if (node.id === 2) {
                    console.log(node.description);
                }
            },
            handleEditConnection(connection) {
            },
            handleDblClick(position) {
                this.$refs.chart.add({
                    id: +new Date(),
                    x: position.x,
                    y: position.y,
                    name: '800',
                    type: 'operation',
                    approvers: [{ id: 1, name: 'Actividad' },],
                });
            },
        }
    };
</script>