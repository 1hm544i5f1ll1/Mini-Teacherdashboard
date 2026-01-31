<?php
/**
 * Age on 1 October (reference date for KG eligibility).
 * academic_year e.g. "2025-2026" -> reference date 1 Oct 2025.
 */
function age_on_1_october($dob, $academicYear = '2025-2026') {
    if (empty($dob)) return null;
    $parts = explode('-', $academicYear);
    $year = (int)($parts[0] ?? date('Y'));
    $ref = new \DateTime("$year-10-01");
    $birth = new \DateTime($dob);
    return $birth->diff($ref)->y;
}

function escape_html($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
