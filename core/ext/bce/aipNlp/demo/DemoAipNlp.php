<?php

// 引入自然语言NLP SDK
require_once '../AipNlp.php';


// 定义常量
const APP_ID = '你的 App ID';
const API_KEY = '你的 Api Key';
const SECRET_KEY = '你的 Secret Key';

// 初始化
$aipNlp = new AipNlp(APP_ID, API_KEY, SECRET_KEY);

// 分词
var_dump($aipNlp->wordseg('你好百度'));

// 词性标注
var_dump($aipNlp->wordpos('你好百度'));

// 词向量
var_dump($aipNlp->wordEmbedding('你好百度'));

// 词相似度
var_dump($aipNlp->wordSimEmbedding('美丽', '漂亮'));

// 短文本相似度
var_dump($aipNlp->simnet('你好百度', '你好世界'));

// 中文DNN语言模型
var_dump($aipNlp->dnnlm('百度是个搜索公司'));

// 情感观点挖掘
var_dump($aipNlp->commentTag('肉夹馍真好吃'));

// 词法分析
var_dump($aipNlp->lexer('百度是个搜索公司'));

// 情感分析
var_dump($aipNlp->sentimentClassify('肉夹馍真好吃'));

// 依存分析
var_dump($aipNlp->depParser('你好百度'));