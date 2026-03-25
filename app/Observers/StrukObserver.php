<?php

namespace App\Observers;

use App\Models\Struk;
use Illuminate\Support\Facades\Auth;

class StrukObserver
{
  /**
   * Handle the Struk "updating" event.
   */
  public function updating(Struk $struk): void
  {
    // If status is changing to "approve" or "tolak" and doesn't already have approval info
    if ($struk->isDirty('status') && in_array($struk->status, ['approve', 'tolak'])) {
      if (!$struk->approved_at) {
        $struk->approved_at = now();
      }
      if (!$struk->approved_by && Auth::check()) {
        $struk->approved_by = Auth::id();
      }
    }
  }
}
