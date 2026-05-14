<?php
/*
Template Name:微话
Version:1.1
Template Url:https://www.emlog.net/template/
Description:基于Material Design的现代化微语主题，支持响应式布局和深色模式
Author:EMtalk
Author Url:https://www.emlog.net/author/index/577
*/
if (!defined('EMLOG_ROOT')) {exit('error!');}
require_once View::getView('module');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="<?php echo $site_key; ?>" />
  <meta name="description" content="<?php echo $site_description; ?>" />
  <meta name="generator" content="emlog" />
  <link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo BLOG_URL; ?>xmlrpc.php?rsd" />
  <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo BLOG_URL; ?>wlwmanifest.xml" />
  <link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
  <title><?php echo $site_title; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>assets/css/style.css?v=1.1">
  <?php doAction('index_head'); ?>
</head>
<body>
<div id="app" class="light-mode">
  <header class="header">
    <div class="container">
      <div class="header-content">
        <a class="logo" href="<?php echo BLOG_URL; ?>">
          <?php if(!empty($options['logo_text'])): ?>
            <span><?php echo $options['logo_text']; ?></span>
          <?php else: ?>
            <span>微话</span>
          <?php endif; ?>
        </a>
        
        <button class="theme-toggle" id="themeToggle" title="切换主题">
          <span class="theme-icon">🌙</span>
        </button>
        
        <div class="header-actions">
          <?php if($uid): ?>
            <div class="user-menu">
              <img src="<?php echo getAvatar($uid); ?>" alt="<?php echo $userData['name']; ?>" class="user-avatar">
              <div class="user-dropdown">
                <a href="<?php echo BLOG_URL; ?>user.php">个人中心</a>
                <a href="<?php echo BLOG_URL; ?>admin/">管理后台</a>
                <a href="<?php echo BLOG_URL; ?>?action=logout">退出登录</a>
              </div>
            </div>
          <?php else: ?>
            <button class="btn btn-primary btn-sm" id="loginBtn">登录</button>
            <button class="btn btn-outline-primary btn-sm" id="registerBtn">注册</button>
          <?php endif; ?>
        </div>
        
        <button class="mobile-menu-btn" id="mobileMenuBtn">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
      
      <div class="mobile-menu" id="mobileMenu">
        <a href="<?php echo BLOG_URL; ?>" class="mobile-nav-item active">首页</a>
        <?php if($uid): ?>
          <a href="<?php echo BLOG_URL; ?>user.php" class="mobile-nav-item">个人中心</a>
          <a href="<?php echo BLOG_URL; ?>?action=logout" class="mobile-nav-item">退出登录</a>
        <?php else: ?>
          <a href="javascript:void(0)" class="mobile-nav-item" id="mobileLoginBtn">登录</a>
          <a href="javascript:void(0)" class="mobile-nav-item" id="mobileRegisterBtn">注册</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <main class="main-content">
