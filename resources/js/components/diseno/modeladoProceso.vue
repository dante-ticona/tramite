<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-md-1">
						<button type="button" class="btn btn-success btn-circle" title="Volver" @click="$router.go(-1)">
							<i class="fa fa-backward white" aria-hidden="true"></i>
						</button>
					</div>
					<div class="col-md-11">
						<h5>{{ plural }}</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						{{ registro.prc_data.prc_codigo }}
					</div>
					<div class="col-md-10">
						{{ registro.prc_data.prc_descripcion }}
					</div>
				</div>
			</div>

			<div class="card-body">
				<div style="width:100%; white-space:nowrap;">
					<span style="display: inline-block; vertical-align: top; width:100px">
						<div id="myPaletteDiv" style="border: solid 1px black; height: 520px"></div>
					</span>
					<span style="display: inline-block; vertical-align: top; width:80%">
						<div id="miDiagramaDiv" style="border: solid 1px black; height: 520px"></div>
					</span>
				</div>

				<div style="margin-top:10px; margin-bottom:10px;">
					<button id="SaveButton" @click="save()">Grabar</button>
					<button @click="load()">Cargar</button>
				</div>

				<textarea id="mySavedModel" style="width:100%;height:150px" disabled>
				</textarea>

				<div style="margin-top:10px; margin-bottom:10px;">
					<button @click="makeSVG()">Renderizar SVG</button>
				</div>
				<div id="generarSVG"></div>
			</div>
		</div>
	</div>
</template>

<script>
import _ from '/bpm/go-debug.js';

	export default {
		name: 'procesos',
		props: ['prc_id'],
		data() {
			return {
				filtro: {},
				plural: 'Modelado',
				singular: 'Proceso',
				errores: [],
				registro: { prc_data: {}, prc_data_campos_clave: {} },
				registros: [],
				catalogos: [],
				file: '',

				myDiagram: null,
				myPalette: null,
				//dataTable: null,
			}
		},

		mounted() {
			//this.listarRegistros();
			this.listarRegistro();
			//this.init();
			//this.dataTable = $('#divTable').DataTable({ responsive: true });
		},

		methods: {

			listarRegistro() {
				let url = "api/procesoXPrcId/" + this.prc_id;
				axios.get(url).then(response => {
					this.registros = response.data.data; //twice data
					this.registros.forEach(function (row) {
						row.prc_data = JSON.parse(row.prc_data);
						row.prc_data_campos_clave = JSON.stringify(JSON.parse(row.prc_data_campos_clave));
					});
					console.log(this.registro);
					this.registro = this.registros[0];
					document.getElementById("mySavedModel").value =	this.registro.prc_modelo;	
					this.init();
				});
			},

/*
			insertarRegistro(e) {
				this.errores = [];
				if (this.errores.length === 0) {
					let gRegistro = this.registro;
					gRegistro.prc_cat_id = this.seleccionado;
					gRegistro.prc_data = JSON.stringify(gRegistro.prc_data);
					gRegistro.prc_data_campos_clave = gRegistro.prc_data_campos_clave;
					gRegistro.prc_usr_id = 1;
					let that = this;
					let url = "api/procesos";
					axios.post(url, gRegistro)
						.then(function (response) {
							that.output = response.data;
							that.listarRegistros();
						})
						.catch(function (error) {
							that.output = error;
						});
				} else {
					e.preventDefault()
				}
			},
*/

/*
			eliminarRegistro() {
				let gRegistro = this.registro;
				gRegistro.prc_usr_id = 1;

				let that = this;
				let url = "api/procesos/" + gRegistro.prc_id;
				axios.post(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
						that.listarRegistros();
					})
					.catch(function (error) {
						that.output = error;
					});
			},
*/

			// inicio implementación librería
			init() {
				if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
				var $ = go.GraphObject.make;  // for conciseness in defining templates

				this.myDiagram =
				$(go.Diagram, "miDiagramaDiv",  // must name or refer to the DIV HTML element
					{
					initialContentAlignment: go.Spot.Center,
					allowDrop: true,  // must be true to accept drops from the Palette
					"LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
					"LinkRelinked": showLinkLabel,
					"animationManager.duration": 800, // slightly longer than default (600ms) animation
					"undoManager.isEnabled": true  // enable undo & redo
					});

				// 777 - start VueJS
				var that = this;

				// when the document is modified, add a "*" to the title and enable the "Save" button
				this.myDiagram.addDiagramListener("Modified", function(e) {
				var button = document.getElementById("SaveButton");
				if (button) button.disabled = ! that.myDiagram.isModified;
				var idx = document.title.indexOf("*");
				if (that.myDiagram.isModified) {
					if (idx < 0) document.title += "*";
				} else {
					if (idx >= 0) document.title = document.title.substr(0, idx);
				}
				});

				// helper definitions for node templates

				function nodeStyle() {
					return [
						// The Node.location comes from the "loc" property of the node data,
						// converted by the Point.parse static method.
						// If the Node.location is changed, it updates the "loc" property of the node data,
						// converting back using the Point.stringify static method.
						new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
						{
						// the Node.location is at the center of each node
						locationSpot: go.Spot.Center,
						//isShadowed: true,
						//shadowColor: "#888",
						// handle mouse enter/leave events to show/hide the ports
						mouseEnter: function (e, obj) { that.showPorts(obj.part, true); },
						mouseLeave: function (e, obj) { that.showPorts(obj.part, false); }
						}
					];
				}

				// Define a function for creating a "port" that is normally transparent.
				// The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
				// and where the port is positioned on the node, and the boolean "output" and "input" arguments
				// control whether the user can draw links from or to the port.
				function makePort(name, spot, output, input) {
				// the port is basically just a small circle that has a white stroke when it is made visible
				return $(go.Shape, "Circle",
						{
							fill: "transparent",
							stroke: null,  // this is changed to "white" in the showPorts function
							desiredSize: new go.Size(8, 8),
							alignment: spot, alignmentFocus: spot,  // align the port on the main Shape
							portId: name,  // declare this object to be a "port"
							fromSpot: spot, toSpot: spot,  // declare where links may connect at this port
							fromLinkable: output, toLinkable: input,  // declare whether the user may draw links to/from here
							cursor: "pointer"  // show a different cursor to indicate potential link point
						});
				}

				// define the Node templates for regular nodes

				var lightText = '#00A9C9';

				function cmDefault(e, obj) {
				var node = obj.part.adornedPart;  // the Node with the context menu
				var buttontext = obj.elt(1);  // the TextBlock
				alert(buttontext.text + " click en el componente " + node.data.key);
				console.log(node);
				}

				this.myDiagram.nodeTemplateMap.add("",  // the default category
				$(go.Node, "Spot", nodeStyle(),
					// the main object is a Panel that surrounds a TextBlock with a rectangular Shape
					$(go.Panel, "Auto",
					$(go.Shape, "Rectangle",
						{ fill: null, stroke: "#00A9C9", strokeWidth: 3 },
						new go.Binding("figure", "figure")),
					$(go.TextBlock,
						{
						font: "7pt Helvetica, Arial, sans-serif",
						stroke: lightText,
						margin: 8,
						maxSize: new go.Size(160, NaN),
						wrap: go.TextBlock.WrapFit,
						editable: true
						},
						new go.Binding("text").makeTwoWay()),
						{ //inicio contextMenu
						contextMenu:                            // define a context menu for each node
							$(go.Adornment, "Spot",               // that has several buttons around
							$(go.Placeholder, { padding: 5 }),  // a Placeholder object
							$("ContextMenuButton", $(go.TextBlock, "Propiedades (bifurcación)"),
								{ alignment: go.Spot.Top, alignmentFocus: go.Spot.Bottom, click: cmDefault }),
							$("ContextMenuButton", $(go.TextBlock, "Reglas de Negocio"),
								{ alignment: go.Spot.Right, alignmentFocus: go.Spot.Left, click: cmDefault })
							)  // end Adornment
						} //fin contextMenu
					),
					// four named ports, one on each side:
					makePort("T", go.Spot.Top, true/*777*/, true),
					makePort("L", go.Spot.Left, true, true),
					makePort("R", go.Spot.Right, true, true),
					makePort("B", go.Spot.Bottom, true, false)
				));

				// Start
				function cmActivity(e, obj) {
					var node = obj.part.adornedPart;  // the Node with the context menu
					var buttontext = obj.elt(1);  // the TextBlock
					alert(buttontext.text + " click en el componente " + node.data.key);
					console.log(node.data);
				}

				this.myDiagram.nodeTemplateMap.add("Activity",  // the Activity new category
				$(go.Node, "Spot", nodeStyle(),
					// the main object is a Panel that surrounds a TextBlock with a rectangular Shape
					$(go.Panel, "Auto",
					$(go.Shape, "RoundedRectangle",
						{ fill: null, stroke: "#00A9C9", strokeWidth: 3 },
						new go.Binding("figure", "figure")),
					$(go.TextBlock,
						{
						font: "8pt Helvetica, Arial, sans-serif",
						stroke: lightText,
						margin: 8,
						maxSize: new go.Size(160, NaN),
						wrap: go.TextBlock.WrapFit,
						editable: true
						},
						new go.Binding("text").makeTwoWay()),
						{ //inicio contextMenu
						contextMenu:                            // define a context menu for each node
							$(go.Adornment, "Spot",               // that has several buttons around
							$(go.Placeholder, { padding: 5 }),  // a Placeholder object
							$("ContextMenuButton", $(go.TextBlock, "Propiedades (actividad)"),
								{ alignment: go.Spot.Top, alignmentFocus: go.Spot.Bottom, click: cmActivity }),
							$("ContextMenuButton", $(go.TextBlock, "Formularios"),
								{ alignment: go.Spot.Right, alignmentFocus: go.Spot.Left, click: cmActivity })
							)  // end Adornment
						} //fin contextMenu
					),
					// four named ports, one on each side:
					makePort("T", go.Spot.Top, true/*777*/, true),
					makePort("L", go.Spot.Left, true, true),
					makePort("R", go.Spot.Right, true, true),
					makePort("B", go.Spot.Bottom, true, false)
				));
				// End

				this.myDiagram.nodeTemplateMap.add("Start",
				$(go.Node, "Spot", nodeStyle(),
					$(go.Panel, "Auto",
					$(go.Shape, "Circle",
						{ minSize: new go.Size(40, 40), fill: "white", stroke: "green", strokeWidth:4}),
					$(go.TextBlock, "",
						{ font: "bold 11pt Helvetica, Arial, sans-serif", stroke: lightText },
						new go.Binding("text"))
					),
					// three named ports, one on each side except the top, all output only:
					//777 makePort("L", go.Spot.Left, true, false),
					makePort("R", go.Spot.Right, true, false),
					makePort("B", go.Spot.Bottom, true, false)
				));

				this.myDiagram.nodeTemplateMap.add("End",
				$(go.Node, "Spot", nodeStyle(),
					$(go.Panel, "Auto",
					$(go.Shape, "Circle",
						{ minSize: new go.Size(40, 40), fill: "white", stroke: "red", strokeWidth: 5}),
					$(go.TextBlock, "",
						{ font: "bold 11pt Helvetica, Arial, sans-serif", stroke: lightText },
						),
					), $(go.TextBlock, "Resultado",
							{   alignment: go.Spot.Bottom, alignmentFocus: go.Spot.Top,
								textAlign: "center",
								font: "8pt Helvetica, Arial, sans-serif",
								maxSize: new go.Size(160, NaN),
								wrap: go.TextBlock.WrapFit,
								editable: true
								}, new go.Binding("text").makeTwoWay()),
					// three named ports, one on each side except the bottom, all input only:
					makePort("T", go.Spot.Top, false, true),
					makePort("L", go.Spot.Left, false, true),
					makePort("B", go.Spot.Bottom, true, false)
					//777 makePort("R", go.Spot.Right, false, true)
				));

				this.myDiagram.nodeTemplateMap.add("Comment",
				$(go.Node, "Auto", nodeStyle(),
					$(go.Shape, "File",
					{ fill: "#f4c842", stroke: null }),
					$(go.TextBlock,
					{
						margin: 5,
						maxSize: new go.Size(200, NaN),
						wrap: go.TextBlock.WrapFit,
						textAlign: "left",
						editable: true,
						font: "8pt Helvetica, Arial, sans-serif",
						stroke: '#454545'
					},
					new go.Binding("text").makeTwoWay())
					// no ports, because no links are allowed to connect with a comment
				));


				// replace the default Link template in the linkTemplateMap
				this.myDiagram.linkTemplate =
				$(go.Link,  // the whole link panel
					{
					routing: go.Link.AvoidsNodes,
					curve: go.Link.JumpOver,
					corner: 5, toShortLength: 4,
					relinkableFrom: true,
					relinkableTo: true,
					reshapable: true,
					resegmentable: true,
					// mouse-overs subtly highlight links:
					mouseEnter: function(e, link) { link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)"; },
					mouseLeave: function(e, link) { link.findObject("HIGHLIGHT").stroke = "transparent"; }
					},
					new go.Binding("points").makeTwoWay(),
					$(go.Shape,  // the highlight shape, normally transparent
					{ isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT" }),
					$(go.Shape,  // the link path shape
					{ isPanelMain: true, stroke: "gray", strokeWidth: 2 }),
					$(go.Shape,  // the arrowhead
					{ toArrow: "standard", stroke: null, fill: "gray"}),
					$(go.Panel, "Auto",  // the link label, normally not visible
					{ visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
					new go.Binding("visible", "visible").makeTwoWay(),
					$(go.Shape, "RoundedRectangle",  // the label shape
						{ fill: "#F8F8F8", stroke: null }),
					$(go.TextBlock, "Yes",  // the label
						{
						textAlign: "center",
						font: "8pt helvetica, arial, sans-serif",
						stroke: "#333333",
						editable: true
						},
						new go.Binding("text").makeTwoWay())
					)
				);

				// Make link labels visible if coming out of a "conditional" node.
				// This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
				function showLinkLabel(e) {
				var label = e.subject.findObject("LABEL");
				if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Diamond");
				}

				// temporary links used by LinkingTool and RelinkingTool are also orthogonal:
				this.myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
				this.myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

				this.load();  // load an initial diagram from some JSON text

				// initialize the Palette that is on the left side of the page
				this.myPalette =
				$(go.Palette, "myPaletteDiv",  // must name or refer to the DIV HTML element
					{
					"animationManager.duration": 800, // slightly longer than default (600ms) animation
					nodeTemplateMap: this.myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
					model: new go.GraphLinksModel([  // specify the contents of the Palette
						{ category: "Comment", text: "Comentario" },
						{ category: "Start", text: "" },
						{ category: "Activity", text: "Actividad" },
						{ text: "???", figure: "Diamond" },
						{ category: "End", text: "Resultado" }
					])
					});

				// The following code overrides GoJS focus to stop the browser from scrolling
				// the page when either the Diagram or Palette are clicked or dragged onto.

				function customFocus() {
				var x = window.scrollX || window.pageXOffset;
				var y = window.scrollY || window.pageYOffset;
				go.Diagram.prototype.doFocus.call(this);
				window.scrollTo(x, y);
				}

				this.myDiagram.doFocus = customFocus;
				this.myPalette.doFocus = customFocus;

			}, // end init

			// Make all ports on a node visible when the mouse is over the node
			showPorts(node, show) {
				var diagram = node.diagram;
				if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
				node.ports.each(function(port) {
					port.stroke = (show ? "red" : null);
				});
			},

			// Show the diagram's model in JSON format that the user may edit
			save() {
				document.getElementById("mySavedModel").value = this.myDiagram.model.toJson();

				// o o o o o o o o o o o
				let gRegistro = this.registro;
				gRegistro.prc_data = JSON.stringify(gRegistro.prc_data);
				gRegistro.prc_data_campos_clave = gRegistro.prc_data_campos_clave;
				gRegistro.prc_modelo = this.myDiagram.model.toJson(); //document.getElementById("mySavedModel").value;
				gRegistro.prc_usr_id = 1;
				console.log("REGISTRO", gRegistro);
				let that = this;
				let url = "api/procesos/" + gRegistro.prc_id;
				axios.put(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
					})
					.catch(function (error) {
						that.output = error;
					});

				this.myDiagram.isModified = false;
			},

			load() {
				this.myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
			},

			// add an SVG rendering of the diagram at the end of this page
			makeSVG() {
				var svg = this.myDiagram.makeSvg({
					scale: 0.5
				});
				svg.style.border = "1px solid black";
				var obj = document.getElementById("generarSVG");
				obj.appendChild(svg);
				if (obj.children.length > 0) {
				obj.replaceChild(svg, obj.children[0]);
				}
			}
			// fin implementación librería

		},

		beforeUpdate: function () {
			//if (this.dataTable) {
			//	this.dataTable.destroy()
			//}
		},

		updated: function () {
			//this.dataTable = $("#divTable").DataTable({ responsive: true })
		}

	}
</script>

<style>
	.btn-circle {
		width: 30px;
		height: 30px;
		padding: 6px 0px;
		border-radius: 15px;
		text-align: center;
		font-size: 12px;
		line-height: 1.42857;
	}

	.combo {
		font-size: 10px;
		height: 25px !important;
	}
</style>