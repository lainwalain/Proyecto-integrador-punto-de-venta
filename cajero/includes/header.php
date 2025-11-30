<?php
// Calcular la URL base automáticamente - Versión mejorada
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_url = "{$protocol}://{$host}/sisventas";
$currentLang = Translation::getCurrentLang();
?>

<div class="header-cajero">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="logo-container me-3">
                        <img src="<?= htmlspecialchars($URL) ?>/images/logo-market-go.png" 
                             alt="<?= htmlspecialchars(t('app_name')) ?>" 
                             class="logo-img" 
                             onerror="this.style.display='none'"
                             loading="lazy">
                    </div>
                    <div>
                        <h1 class="logo"><?= htmlspecialchars(t('app_name')) ?></h1>
                        <p class="mb-0 text-light"><?= htmlspecialchars(t('system_name')) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <button class="language-toggle globe-toggle" id="languageToggle" 
                        title="<?= htmlspecialchars(t('toggle_language')) ?>"
                        aria-label="<?= htmlspecialchars(t('toggle_language')) ?>">
                    <span class="globe-icon" aria-hidden="true">🌍</span>
                    <span class="language-text"><?= $currentLang === 'es' ? 'ES' : 'EN' ?></span>
                    <span class="language-arrow" aria-hidden="true">▼</span>
                </button>
                
                <button class="theme-toggle" id="themeToggle" 
                        title="<?= htmlspecialchars(t('toggle_theme')) ?>"
                        aria-label="<?= htmlspecialchars(t('toggle_theme')) ?>">
                    <span class="theme-icon" aria-hidden="true">🌙</span>
                </button>
                
                <span class="user-info">
                    <?= htmlspecialchars(t('cashier')) ?>: 
                    <?= htmlspecialchars($_SESSION['nombres'] ?? 'Usuario') ?>
                </span>
                
                <a href="<?= htmlspecialchars($URL) ?>/login" 
                   class="btn btn-danger btn-sm ms-2 logout-btn"
                   onclick="return confirm('<?= htmlspecialchars(t('confirm_logout')) ?>')">
                    <span class="logout-icon" aria-hidden="true">🚪</span>
                    <?= htmlspecialchars(t('logout')) ?>
                </a>
            </div>
        </div>
    </div>
</div>