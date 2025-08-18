<?php

namespace App\Helpers;

class ShippingHelper
{
    /**
     * Accumulate ONE compressed SKU into the running compressed layer/box.
     *
     * @param int   $qty
     * @param float $L   Rotated per-unit length  (L ≥ W ≥ H)
     * @param float $W   Rotated per-unit width
     * @param float $H   Rotated per-unit height (smallest edge)
     * @param float &$comp_layer_L  current layer length   (by ref)
     * @param float &$comp_layer_W  current layer width    (by ref)
     * @param float &$comp_layer_H  current layer height   (by ref)
     * @param float &$comp_box_L    compressed box length  (by ref)
     * @param float &$comp_box_W    compressed box width   (by ref)
     * @param float &$comp_box_H    compressed box height  (by ref)
     * @param float $heightCap      max layer height (default 30")
     * @param float $ratio          thickness compression ratio (default 0.6)
     * @param float $floor          min flattened thickness (default 0.25")
     * @param int   $searchCap      try up to N items per layer to minimize volume
     * @return array [L_box, W_box, H_box] chosen for this SKU (for debugging/inspect)
     */
    public static function accumulateCompressedItem(
        $qty,
        $L, $W, $H,
        &$comp_layer_L, &$comp_layer_W, &$comp_layer_H,
        &$comp_box_L,   &$comp_box_W,   &$comp_box_H,
        $heightCap = 30.0, $ratio = 0.6, $floor = 0.25, $searchCap = 12
    ) {
        $qty       = (int)$qty;
        $L = (float)$L; $W = (float)$W; $H = (float)$H;
        $heightCap = (float)$heightCap;
        $ratio     = (float)$ratio;
        $floor     = (float)$floor;
        $searchCap = (int)$searchCap;

        // 1) compress thickness
        $H_eff = max($H * $ratio, $floor);

        // 2) search best per-layer count to minimize volume under height cap
        $best = null; $bestVol = INF;
        $upper = max(1, min($qty, $searchCap));
        for ($n = 1; $n <= $upper; $n++) {
            $cols = (int)ceil(sqrt($n));
            $rows = (int)ceil($n / max(1, $cols));

            $L_box = $L * $cols;
            $W_box = $W * $rows;
            $layers = (int)ceil($qty / $n);
            $H_box  = $H_eff * $layers;

            if ($H_box > $heightCap) {
                continue; // respect layer height cap
            }

            $vol = $L_box * $W_box * $H_box;
            if ($vol < $bestVol) {
                $bestVol = $vol;
                $best = [$L_box, $W_box, $H_box];
            }
        }

        if ($best === null) {
            // fallback: tall column if nothing fits the cap
            $best = [$L, $W, $H_eff * $qty];
        }

        list($L_box, $W_box, $H_box) = $best;

        // 3) place into the current compressed layer (choose extend L or extend W)
        // Option A: extend length
        $optA_L = $comp_layer_L + $L_box;
        $optA_W = max($comp_layer_W, $W_box);
        $optA_H = max($comp_layer_H, $H_box);

        // Option B: extend width
        $optB_W = $comp_layer_W + $W_box;
        $optB_L = max($comp_layer_L, $L_box);
        $optB_H = max($comp_layer_H, $H_box);

        $fitsA = ($optA_H <= $heightCap);
        $fitsB = ($optB_H <= $heightCap);

        if ($fitsA || $fitsB) {
            if ($fitsA && $fitsB) {
                $volA = $optA_L * $optA_W * $optA_H;
                $volB = $optB_L * $optB_W * $optB_H;
                if ($volA <= $volB) {
                    $comp_layer_L = $optA_L; $comp_layer_W = $optA_W; $comp_layer_H = $optA_H;
                } else {
                    $comp_layer_L = $optB_L; $comp_layer_W = $optB_W; $comp_layer_H = $optB_H;
                }
            } elseif ($fitsA) {
                $comp_layer_L = $optA_L; $comp_layer_W = $optA_W; $comp_layer_H = $optA_H;
            } else { // fitsB
                $comp_layer_L = $optB_L; $comp_layer_W = $optB_W; $comp_layer_H = $optB_H;
            }
        } else {
            // close current layer into compressed box, start new layer with this block
            $comp_box_L = max($comp_box_L, $comp_layer_L);
            $comp_box_W = max($comp_box_W, $comp_layer_W);
            $comp_box_H += $comp_layer_H;

            $comp_layer_L = $L_box;
            $comp_layer_W = $W_box;
            $comp_layer_H = $H_box;
        }

        return [$L_box, $W_box, $H_box];
    }

    /**
     * Close the last open compressed layer into the compressed box.
     * Call this ONCE after finishing the loops.
     */
    public static function finalizeCompressedBox(
        &$comp_layer_L, &$comp_layer_W, &$comp_layer_H,
        &$comp_box_L,   &$comp_box_W,   &$comp_box_H
    ) {
        $comp_box_L = max($comp_box_L, $comp_layer_L);
        $comp_box_W = max($comp_box_W, $comp_layer_W);
        $comp_box_H += $comp_layer_H;

        // optional: reset layer
        $comp_layer_L = $comp_layer_W = $comp_layer_H = 0.0;
    }
}
