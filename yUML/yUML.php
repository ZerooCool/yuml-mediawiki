<?php
// Author: Sindri Traustason http://sindri.info

// Avoid unstubbing $wgParser on setHook() too early on modern (1.12+) MW versions, as per r35980
if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
        $wgHooks['ParserFirstCallInit'][] = 'efYUMLInit';
} else {
        // Otherwise do things the old fashioned way
        $wgExtensionFunctions[] = 'efYUMLInit';
}

function efYUMLInit() {
        global $wgParser;
        $wgParser->setHook( 'classdiagram', 'efClassdiagramRender' );
        $wgParser->setHook( 'usecase', 'efUsecaseRender' );
        return true;
}

function yUMLRenderDiagram( $input, $args, $diagramType ) {
        $type = "";
        if(!empty($args["type"])){
                $type = "/".$args["type"];
        }
        $scale = "";
        if(!empty($args["scale"])){
                $scale=";scale:".$args["scale"];
        }
        $yumldir = "";
        if(!empty($args["dir"])){
                $yumldir=";dir:".$args["dir"];
        }
        $uml_code = preg_replace(
                array("/\n/", "/,,/"),
                array(", ",   ","   ),
                trim($input));
        $output = "<img src=\"https://yUML.me/diagram".$type.$scale.$yumldir."/".$diagramType."/";
        return $output.htmlspecialchars( $uml_code )."\"/>";
}

function efClassdiagramRender( $input, $args, $parser ) {
        return yUMLRenderDiagram( $input, $args, "class" );
}

function efUsecaseRender( $input, $args, $parser ) {
        return yUMLRenderDiagram( $input, $args, "usecase" );
}
?>
